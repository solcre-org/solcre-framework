<?php

namespace Solcre\SolcreFramework2\Common;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Solcre\SolcreFramework2\Filter\FilterInterface;

class BaseRepository extends EntityRepository
{
    private const NOT_NULL_FILTER = '!null';
    private const ORDER_BY_RELATION_SEPARATOR = '.';
    protected $filters = [];

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    public function findBy(array $params, array $orderBy = null, $limit = null, $offset = null)
    {
        //Pre find by
        $filtersOptions = $this->preFindBy($params);
        //Legacy
        if (empty($filtersOptions['fields'])) {
            $result = parent::findBy($params, $orderBy, $limit, $offset);
        } else {
            //Execute
            $query = $this->getFindByQuery($params, $orderBy, $filtersOptions);
            $result = $query->getResult();
        }

        //Post find by
        $this->postFindBy($filtersOptions);

        return $result;
    }

    protected function preFindBy(array &$params): array
    {
        $filtersOptions = [
            'fields' => $params['fields'] ?? false,
            'expand' => $params['expand'] ?? false,
            'search' => $params['query'] ?? false,
        ];
        unset($params['query'], $params['fields'], $params['expand'], $params['page']);
        return $filtersOptions;
    }

    protected function getFindByQuery(array $params, array $orderBy = null, array $filterOptions = [])
    {
        //Table alias
        $tableAlias = 'a';

        //Create query
        $qb = $this->createQueryBuilder($tableAlias);

        //Set fields select
        $qb->select($this->getFieldsSelect($tableAlias, $filterOptions['fields']));

        //Add DQL Wheres
        $this->setWhereSql($tableAlias, $qb, $params);

        //Add order by to dql
        if (! empty($orderBy)) {
            $this->setOrderBy($params, $orderBy, $qb, $tableAlias);
        }

        $searchTerm = $filterOptions['search'] ?? false;
        if ($searchTerm) {
            $this->applySearch($tableAlias, $qb, $searchTerm);
        }

        return $qb->getQuery();
    }

    protected function getFieldsSelect($tableAlias, $fieldsFilterQuery)
    {
        //Select all fields by default
        $fieldsSelect = $tableAlias;

        //Check query fields
        if (! empty($fieldsFilterQuery)) {
            $fieldsFilter = \is_string($fieldsFilterQuery) ? explode(',', $fieldsFilterQuery) : $fieldsFilterQuery;


            //parse selection str
            $selectedFields = ['id'];
            $fields = $this->_em->getClassMetadata($this->_entityName)->fieldNames;

            //Foreach field
            foreach ($fields as $key => $fieldName) {
                //Selected field?
                if (\in_array($fieldName, $fieldsFilter)) {
                    $selectedFields[] = $fieldName;
                }
            }

            //Selet DQL base query
            $fieldsSelect = [sprintf('partial %s.{%s}', $tableAlias, implode(',', $selectedFields))];
        }

        return $fieldsSelect;
    }

    protected function setWhereSql($tableAlias, QueryBuilder $qb, $params): void
    {
        unset($params['sort']);
        if (\is_array($params) && ! empty($params)) {
            $and = $qb->expr()->andX();
            foreach ($params as $fieldName => $fieldValue) {
                $alias = \sprintf('%s.%s', $tableAlias, $fieldName);
                if (\is_array($fieldValue) && $this->entityHasAssociation($fieldName) && $this->hasStringKeys($fieldValue)) {
                    $qb->join($alias, $fieldName);
                    foreach ($fieldValue as $key => $value) {
                        $alias = \sprintf('%s.%s', $fieldName, $key);
                        $this->setWhereClause($qb, $value, $alias, $and);
                    }
                } else {
                    $this->setWhereClause($qb, $fieldValue, $alias, $and);
                }
            }

            $qb->andWhere($and);
        }
    }

    protected function entityHasAssociation($fieldName): bool
    {
        return $this->_em->getClassMetadata($this->_entityName)->hasAssociation($fieldName);
    }

    private function hasStringKeys(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private function setWhereClause(QueryBuilder $qb, $fieldValue, $alias, Andx $and): void
    {
        $expression = null;
        if ($fieldValue === null || $fieldValue === 'null') {
            $expression = $qb->expr()->isNull($alias);
            $and->add($expression);
        } elseif (\is_array($fieldValue) && ! empty($fieldValue)) {
            $expression = $qb->expr()->in($alias, $fieldValue);
            $and->add($expression);
        } elseif ($fieldValue === self::NOT_NULL_FILTER) {
            $expression = $qb->expr()->isNotNull($alias);
            $and->add($expression);
        } elseif (\is_string($fieldValue)) {
            if (strpos($fieldValue, '~') !== false) {
                $valueParts = explode('~', $fieldValue);
                if (\is_array($valueParts) && \count($valueParts) === 2) {
                    $paramKey = \sprintf(':%s', $this->getUniqueKeyParam($qb));
                    $expression = $qb->expr()->like($alias, $paramKey);
                    $qb->setParameter($paramKey, '%' . $valueParts[1] . '%');
                    $and->add($expression);
                }
            }

            if (strpos($fieldValue, '|') !== false) {
                $valueParts = explode('|', $fieldValue);
                if (\is_array($valueParts) && \count($valueParts) === 2) {
                    if (! empty($valueParts[0])) {
                        $paramKey = \sprintf(':%s', $this->getUniqueKeyParam($qb));
                        $expression = $qb->expr()->gte($alias, $paramKey);
                        $qb->setParameter($paramKey, $valueParts[0]);
                        $and->add($expression);
                    }

                    if (! empty($valueParts[1])) {
                        $paramKey = \sprintf(':%s', $this->getUniqueKeyParam($qb));
                        $expression = $qb->expr()->lte($alias, $paramKey);
                        $qb->setParameter($paramKey, $valueParts[1]);
                        $and->add($expression);
                    }
                }
            }
        }

        if ($fieldValue !== null && $expression === null) {
            $paramKey = \sprintf(':%s', $this->getUniqueKeyParam($qb));
            $expression = $qb->expr()->eq($alias, $paramKey);
            $qb->setParameter($paramKey, $fieldValue);
            $and->add($expression);
        }
    }

    protected function getUniqueKeyParam(QueryBuilder $qb): string
    {
        $paramName = 'value';
        $count = 0;
        while ($qb->getParameter($paramName) instanceof Parameter) {
            $paramName .= $count;
            $count++;
        }
        return $paramName;
    }

    protected function setOrderBy(array &$params, array $orderBy, QueryBuilder $qb, $tableAlias): void
    {
        if ($orderBy !== null && \is_array($orderBy)) {
            foreach ($orderBy as $fieldName => $direction) {
                if ($this->isAssociationSort($fieldName)) {
                    $this->processAssociationSort($tableAlias, $fieldName, $direction, $qb);
                } elseif ($this->entityHasField($this->_entityName, $fieldName)) {
                    $qb->addOrderBy($tableAlias . '.' . $fieldName, $direction);
                }
            }

            if (isset($params['sort'])) {
                unset($params['sort']);
            }
        }
    }

    private function isAssociationSort($fieldName): bool
    {
        return (bool)\strpos($fieldName, self::ORDER_BY_RELATION_SEPARATOR);
    }

    private function processAssociationSort($tableAlias, $fieldName, $direction, QueryBuilder $qb): void
    {
        $sortParts = \explode(self::ORDER_BY_RELATION_SEPARATOR, $fieldName);
        [$sortAssociationFieldName, $sortAssociationFieldToSort] = $sortParts;
        if ($this->entityHasAssociation($sortAssociationFieldName) && $this->entityAssociationHasFieldName($sortAssociationFieldName, $sortAssociationFieldToSort)) {
            $associationKey = $this->getAliasFromJoin($tableAlias, $sortAssociationFieldName, $qb);
            if ($associationKey === null) {
                $associationKey = \uniqid($sortAssociationFieldName, false);
                $qb->join($tableAlias . '.' . $sortAssociationFieldName, $associationKey);
            }

            $qb->addOrderBy($associationKey . '.' . $sortAssociationFieldToSort, $direction);
        }
    }

    protected function entityAssociationHasFieldName($fieldName, $fieldAssociationName): bool
    {
        $class = $this->_em->getClassMetadata($this->_entityName)->getAssociationTargetClass($fieldName);
        return $this->entityHasField($class, $fieldAssociationName);
    }

    protected function entityHasField($class, $fieldAssociationName): bool
    {
        return $this->_em->getClassMetadata($class)->hasField($fieldAssociationName);
    }

    private function getAliasFromJoin($tableAlias, $table, QueryBuilder $qb): ?string
    {
        $joins = $qb->getDQLPart('join');
        if (! empty($joins) && \array_key_exists($tableAlias, $joins)) {
            $tableJoins = $joins[$tableAlias];
            foreach ($tableJoins as $tableJoin) {
                if ($tableJoin->getJoin() === $tableAlias . '.' . $table) {
                    return $tableJoin->getAlias();
                }
            }
        }
        return null;
    }

    protected function applySearch($tableAlias, QueryBuilder $qb, $searchTerm): void
    {
        $searchableFields = $this->getSearchableFields();

        if (\is_array($searchableFields) && count($searchableFields)) {
            $or = $qb->expr()->orX();
            foreach ($searchableFields as $field) {
                if ($field['type'] === 'string') {
                    $or->add($qb->expr()->like($tableAlias . '.' . $field['name'], ':searchTerm'));
                    $qb->setParameter('searchTerm', '%' . $searchTerm . '%');
                }
            }
            $qb->andWhere($or);
        }
    }

    protected function getSearchableFields(): array
    {
        $searchableFields = [];
        $fieldMappings = $this->getClassMetadata()->fieldMappings;
        if (\is_array($fieldMappings) && count($fieldMappings)) {
            foreach ($fieldMappings as $key => $field) {
                //Check searchable options
                if (isset($field['options']['searchable'], $field['type'], $field['fieldName']) && $field['options']['searchable']) {
                    $searchableFields[] = [
                        'type' => $field['type'],
                        'name' => $field['fieldName'],
                    ];
                }
            }
        }
        return $searchableFields;
    }

    protected function postFindBy($filtersOptions): void
    {
        $this->filter($filtersOptions);
    }

    protected function filter(array $options): void
    {
        if (count($this->filters) > 0) {
            //Created entity for filters
            $entityName = $this->getEntityName();
            $entity = new $entityName();

            //For each filter
            foreach ($this->filters as $name => $filter) {
                //Is filter interface?
                if ($filter instanceof FilterInterface) {
                    //Can filter?
                    if ($filter->canFilter($options)) {
                        //Load options
                        $filter->prepareOptions($options);
                        //Filter
                        $filter->filter($entity);
                    } else {
                        //remove filter
                        $filter->removeFilter($entity);
                    }
                }
            }
        }
    }

    public function findOneBy(array $params, array $orderBy = null)
    {
        //Prepare filter options
        $filtersOptions = [
            'fields' => $params['fields'] ?? [],
            'expand' => $params['expand'] ?? [],
        ];
        //Remove fields to prevent entity conflicts
        unset($params['fields']);
        unset($params['expand']);
        //Find one by
        $entity = parent::findOneBy($params, $orderBy);
        //Execute  filters
        $this->filter($filtersOptions);
        return $entity;
    }

    protected function isParamSet(array $params, $key)
    {
        return (isset($params[$key]) && ! empty($params[$key]));
    }

    public function findByPaginated(array $params, array $orderBy = null, $limit = null, $offset = null)
    {
        //Pre find by
        $filtersOptions = $this->preFindBy($params);

        //Execute
        $query = $this->getFindByQuery($params, $orderBy, $filtersOptions);

        //Create doctrine paginator
        $doctrinePaginator = $this->getDoctrinePaginator($query);

        //Post find by
        $this->postFindBy($filtersOptions);

        return $doctrinePaginator;
    }

    protected function getDoctrinePaginator($query, $fetchJoinCollection = true)
    {
        $ormPaginator = new OrmPaginator($query, $fetchJoinCollection);
        return new DoctrinePaginator($ormPaginator);
    }
}
