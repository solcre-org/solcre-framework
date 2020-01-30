<?php

namespace Solcre\SolcreFramework2\Common;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Solcre\SolcreFramework2\Filter\FilterInterface;
use function array_key_exists;
use function count;
use function explode;
use function in_array;
use function is_array;
use function is_string;
use function sprintf;
use function strpos;
use function uniqid;

class BaseRepository extends EntityRepository
{
    private const MINIMUM_WHERE_PARTS = 2;
    private const NOT_NULL_FILTER = '!null';
    private const ORDER_BY_RELATION_SEPARATOR = '.';
    protected $filters = [];

    public function addFilter(FilterInterface $filter): void
    {
        $this->filters[$filter->getName()] = $filter;
    }

    public function findBy(array $params, array $orderBy = null, $limit = null, $offset = null)
    {
        $filtersOptions = $this->preFindBy($params);

        $query = $this->getFindByQuery($params, $orderBy, $filtersOptions);
        $result = $query->getResult();

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

    protected function getFindByQuery(array $params, array $orderBy = null, array $filterOptions = []): Query
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
        if (! empty($orderBy))
        {
            $this->setOrderBy($orderBy, $qb, $tableAlias);
        }

        $searchTerm = $filterOptions['search'] ?? false;
        if ($searchTerm)
        {
            $this->applySearch($tableAlias, $qb, $searchTerm);
        }

        return $qb->getQuery();
    }

    protected function getFieldsSelect($tableAlias, $fieldsFilterQuery)
    {
        //Select all fields by default
        $fieldsSelect = $tableAlias;

        //Check query fields
        if (! empty($fieldsFilterQuery))
        {
            $fieldsFilter = is_string($fieldsFilterQuery) ? explode(',', $fieldsFilterQuery) : $fieldsFilterQuery;


            //parse selection str
            $selectedFields = ['id'];
            $fields = $this->_em->getClassMetadata($this->_entityName)->fieldNames;

            //Foreach field
            foreach ($fields as $key => $fieldName)
            {
                //Selected field?
                if (in_array($fieldName, $fieldsFilter, true))
                {
                    $selectedFields[] = $fieldName;
                }
            }

            $fieldsSelect = [sprintf('partial %s.{%s}', $tableAlias, implode(',', $selectedFields))];
        }

        return $fieldsSelect;
    }

    protected function setWhereSql($tableAlias, QueryBuilder $qb, $params): void
    {
        unset($params['sort'], $params['size']);
        if (\is_array($params) && ! empty($params))
        {
            $and = $qb->expr()->andX();
            $or = $qb->expr()->orX();
            foreach ($params as $fieldName => $fieldValue)
            {
                $alias = $this->getAliasFromFieldInTable($tableAlias, $fieldName);
                if (\is_array($fieldValue) && $this->entityHasAssociation($fieldName) && $this->hasStringKeys($fieldValue))
                {
                    $qb->join($alias, $fieldName);
                    foreach ($fieldValue as $key => $value)
                    {
                        $alias = $this->getAliasFromFieldInTable($fieldName, $key);
                        $this->setWhereClause($qb, $value, $alias, $and, $or);
                    }
                }
                else
                {
                    $this->setWhereClause($qb, $fieldValue, $alias, $and, $or);
                }

                $this->setWhereClause($qb, $fieldValue, $alias, $and);
            }

            $qb->andWhere($and);
            $qb->orWhere($or);
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
        if ($fieldValue === null || $fieldValue === 'null')
        {
            $this->isNullWhereClause($qb, $alias, $and, $expression);
        }

        if (is_array($fieldValue) && ! empty($fieldValue))
        {
            $this->inWhereClause($qb, $fieldValue, $alias, $and, $expression);
        }

        if (is_string($fieldValue))
        {
            $this->setWhereClauseWithStringValue($qb, $fieldValue, $alias, $and, $expression, $valueParts, $paramKey);
        }

        if ($fieldValue !== null && $expression === null)
        {
            $this->equalWhereClause($qb, $fieldValue, $alias, $and, $paramKey, $expression);
        }
    }

    private function isNullWhereClause(QueryBuilder $qb, $alias, Andx $and, &$expression): void
    {
        $expression = $qb->expr()->isNull($alias);
        $and->add($expression);
    }

    private function inWhereClause(QueryBuilder $qb, $fieldValue, $alias, Andx $and, &$expression): void
    {
        $expression = $qb->expr()->in($alias, $fieldValue);
        $and->add($expression);
    }

    private function setWhereClauseWithStringValue(QueryBuilder $qb, $fieldValue, $alias, Andx $and, &$expression, &$valueParts, &$paramKey): void
    {
        if ($fieldValue === self::NOT_NULL_FILTER)
        {
            $this->isNotNullWhereClause($qb, $alias, $and, $expression);
        }

        if (strpos($fieldValue, '~') !== false)
        {
            $this->isLikeWhereClause($qb, $fieldValue, $alias, $and, $valueParts, $paramKey, $expression);
        }

        if (strpos($fieldValue, '|') !== false)
        {
            $this->compareWhereClause($qb, $fieldValue, $alias, $and, $valueParts, $paramKey, $expression);
        }
    }

    private function isNotNullWhereClause(QueryBuilder $qb, $alias, Andx $and, &$expression): void
    {
        $expression = $qb->expr()->isNotNull($alias);
        $and->add($expression);
    }

    private function isLikeWhereClause(QueryBuilder $qb, $fieldValue, $alias, Andx $and, &$valueParts, &$paramKey, &$expression): void
    {
        $valueParts = explode('~', $fieldValue);
        if (is_array($valueParts) && count($valueParts) === self::MINIMUM_WHERE_PARTS)
        {
            $paramKey = sprintf(':%s', $this->getUniqueKeyParam($qb));
            $expression = $qb->expr()->like($alias, $paramKey);
            $qb->setParameter($paramKey, '%' . $valueParts[1] . '%');
            $and->add($expression);
        }
    }

    protected function getUniqueKeyParam(QueryBuilder $qb): string
    {
        $paramName = 'value';
        $count = 0;
        while ($qb->getParameter($paramName) instanceof Parameter)
        {
            $paramName .= $count;
            $count++;
        }
        return $paramName;
    }

    private function compareWhereClause(QueryBuilder $qb, $fieldValue, $alias, Andx $and, &$valueParts, &$paramKey, &$expression): void
    {
        $valueParts = explode('|', $fieldValue);
        if (is_array($valueParts) && count($valueParts) === self::MINIMUM_WHERE_PARTS)
        {
            if (! empty($valueParts[0]))
            {
                $paramKey = sprintf(':%s', $this->getUniqueKeyParam($qb));
                $expression = $qb->expr()->gte($alias, $paramKey);
                $qb->setParameter($paramKey, $valueParts[0]);
                $and->add($expression);
            }

            if (! empty($valueParts[1]))
            {
                $paramKey = sprintf(':%s', $this->getUniqueKeyParam($qb));
                $expression = $qb->expr()->lte($alias, $paramKey);
                $qb->setParameter($paramKey, $valueParts[1]);
                $and->add($expression);
            }
        }
    }

    private function equalWhereClause(QueryBuilder $qb, $fieldValue, $alias, Andx $and, &$paramKey, &$expression): void
    {
        $paramKey = sprintf(':%s', $this->getUniqueKeyParam($qb));
        $expression = $qb->expr()->eq($alias, $paramKey);
        $qb->setParameter($paramKey, $fieldValue);
        $and->add($expression);
    }

    protected function setOrderBy(array $orderBy, QueryBuilder $qb, string $tableAlias): void
    {
        if (! empty($orderBy))
        {
            foreach ($orderBy as $fieldName => $direction)
            {
                if ($this->isAssociationSort($fieldName))
                {
                    $this->processAssociationSort($tableAlias, $fieldName, $direction, $qb);
                }
                elseif ($this->entityHasField($this->_entityName, $fieldName))
                {
                    $qb->addOrderBy($tableAlias . '.' . $fieldName, $direction);
                }
            }
        }
    }

    private function isAssociationSort($fieldName): bool
    {
        return (bool)\strpos($fieldName, self::ORDER_BY_RELATION_SEPARATOR);
    }

    private function processAssociationSort($tableAlias, $fieldName, $direction, QueryBuilder $qb): void
    {
        $sortParts = explode(self::ORDER_BY_RELATION_SEPARATOR, $fieldName);
        [$sortAssociationFieldName, $sortAssociationFieldToSort] = $sortParts;
        if ($this->entityHasAssociation($sortAssociationFieldName) && $this->entityAssociationHasFieldName($sortAssociationFieldName, $sortAssociationFieldToSort))
        {
            $associationKey = $this->getAliasFromJoin($tableAlias, $sortAssociationFieldName, $qb);
            if ($associationKey === null)
            {
                $associationKey = uniqid($sortAssociationFieldName, false);
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
        if (! empty($joins) && array_key_exists($tableAlias, $joins))
        {
            $tableJoins = $joins[$tableAlias];
            foreach ($tableJoins as $tableJoin)
            {
                if ($tableJoin->getJoin() === $tableAlias . '.' . $table)
                {
                    return $tableJoin->getAlias();
                }
            }
        }
        return null;
    }

    protected function applySearch($tableAlias, QueryBuilder $qb, $searchTerm): void
    {
        $searchableFields = $this->getSearchableFields();

        if (is_array($searchableFields) && count($searchableFields))
        {
            $or = $qb->expr()->orX();
            foreach ($searchableFields as $field)
            {
                if ($field['type'] === 'string')
                {
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
        if (is_array($fieldMappings) && count($fieldMappings))
        {
            foreach ($fieldMappings as $key => $field)
            {
                //Check searchable options
                if (isset($field['options']['searchable'], $field['type'], $field['fieldName']) && $field['options']['searchable'])
                {
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
        if (count($this->filters) > 0)
        {
            $entityName = $this->getEntityName();
            $entity = new $entityName();

            foreach ($this->filters as $name => $filter)
            {
                if ($filter instanceof FilterInterface)
                {
                    if ($filter->canFilter($options))
                    {
                        $filter->prepareOptions($options);
                        $filter->filter($entity);
                        continue;
                    }

                    $filter->removeFilter($entity);
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
        unset($params['fields'], $params['expand']);
        //Find one by
        $entity = parent::findOneBy($params, $orderBy);
        //Execute  filters
        $this->filter($filtersOptions);
        return $entity;
    }

    protected function isParamSet(array $params, $key): bool
    {
        return (isset($params[$key]) && ! empty($params[$key]));
    }

    public function findByPaginated(array $params, array $orderBy = null, $limit = null, $offset = null): DoctrinePaginator
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

    protected function getDoctrinePaginator($query): DoctrinePaginator
    {
        $ormPaginator = $this->createOrmPaginator($query, true);
        return $this->createDoctrinePaginator($ormPaginator);
    }

    private function createOrmPaginator($query, $fetchJoinCollection): OrmPaginator
    {
        return new OrmPaginator($query, $fetchJoinCollection);
    }

    private function createDoctrinePaginator($ormPaginator): DoctrinePaginator
    {
        return new DoctrinePaginator($ormPaginator);
    }

    protected function getDoctrinePaginatorWithoutJoinCollection($query): DoctrinePaginator
    {
        $ormPaginator = $this->createOrmPaginator($query, false);
        return $this->createDoctrinePaginator($ormPaginator);
    }

    protected function getAliasFromFieldInTable(string $tableName, string $fieldName): string
    {
        return sprintf('%s.%s', $tableName, $fieldName);
    }
}
