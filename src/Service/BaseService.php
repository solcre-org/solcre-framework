<?php

namespace Solcre\SolcreFramework2\Service;

use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Exception;
use MegaPharma\V1\Domain\Common\Service\IdentityService;
use MegaPharma\V1\Domain\Common\Strategy\NullIntegerType;
use ReflectionClass;
use Solcre\SolcreFramework2\Common\BaseRepository;
use Solcre\SolcreFramework2\Entity\PaginatedResult;
use Solcre\SolcreFramework2\Filter\FilterInterface;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Zend\Paginator\Paginator;

abstract class BaseService
{

    protected $entityManager;
    protected $repository;
    protected $entityName;
    protected $loggedUser;
    protected $identityService;
    protected $currentPage = 1;
    protected $itemsCountPerPage = 50;

    /**
     * Additional attributes to render with the collection
     *
     * @var array
     */
    protected $additionalAttributes;

    public function __construct(EntityManager $entityManager, IdentityService $identityService = null)
    {
        $this->entityManager = $entityManager;
        $this->identityService = $identityService;
        $this->entityName = $this->getEntityName();
        if ($this->entityName !== null) {
            $this->repository = $this->entityManager->getRepository($this->entityName);
        }
        $this->loggedUser = null;
        $this->entityManager->getFilters()->enable('soft-deleteable');
        $this->additionalAttributes = [];
    }

    public function getEntityName(): ?string
    {
        $namespaceName = (new ReflectionClass($this))->getNamespaceName();
        $className = (new ReflectionClass($this))->getShortName();
        $entityName = substr($className, 0, strpos($className, 'Service'));
        $entityNamespace = str_replace('Service', 'Entity', $namespaceName);
        $entityFull = $entityNamespace . '\\' . $entityName;
        if (\class_exists($entityFull)) {
            return $entityFull;
        }
        return null;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository($repository): void
    {
        $this->repository = $repository;
    }

    public function setConfiguration($configuration): void
    {
        $this->configuration = $configuration;
    }

    public function addFilter($filter): void
    {
        if ($this->repository instanceof BaseRepository && $filter instanceof FilterInterface) {
            $this->repository->addFilter($filter);
        }
    }

    public function add($data)
    {
        $entityObj = $this->prepareEntity($data);
        $this->entityManager->persist($entityObj);
        $this->entityManager->flush();
        return $entityObj;
    }

    public function prepareEntity($data, $entity = null, $strategies = [])
    {
        if ($entity === null) {
            $entity = $this->getEntityName();
            $entity = new $entity;
        }
        $hydrator = new EntityHydrator($this->entityManager);
        if (! empty($strategies)) {
            foreach ($strategies as $strategy) {
                $hydrator->addStrategy($strategy['field'], $strategy['strategy']);
            }
        }
        return $hydrator->hydrate($data, $entity);
    }

    public function fetchOne($id, array $params = [])
    {
        $params['id'] = $id;
        return $this->repository->findOneBy($params);
    }

    public function fetchBy($params = null, $orderBy = null)
    {
        return $this->repository->findOneBy($params, $orderBy);
    }

    public function fetchAll($params = null, $orderBy = null): array
    {
        if (! empty($params) || ! empty($orderBy)) {
            return $this->repository->findBy((array)$params, $orderBy);
        }
        return $this->repository->findAll();
    }

    public function fetchAllPaginated($params = null, $orderBy = null): PaginatedResult
    {
        $doctrinePaginator = $this->repository->findByPaginated((array)$params, $orderBy);
        return $this->paginateResults($doctrinePaginator);
    }

    protected function paginateResults(DoctrinePaginator $doctrinePaginator): PaginatedResult
    {
        //Get options
        $currentPage = (int)$this->getCurrentPage();
        $pageSize = (int)$this->getItemsCountPerPage();

        //Here is where configures the paginator options and iterate for doctrinePaginator
        //The doctrine paginator with getIterator, rise the queries taking page size
        //and current page params.
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setItemCountPerPage($pageSize);
        $paginator->setCurrentPageNumber($currentPage);

        //Get array result
        $arrayResult = [];
        foreach ($paginator as $item) {
            $arrayResult[] = $item;
        }
        //Fill the iterator and return it
        return new PaginatedResult($arrayResult, $doctrinePaginator->count(), $this->additionalAttributes);
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage): void
    {
        if (empty($currentPage)) {
            return;
        }

        $this->currentPage = $currentPage;
    }

    public function getItemsCountPerPage(): int
    {
        return $this->itemsCountPerPage;
    }

    public function setItemsCountPerPage($itemsCountPerPage): void
    {
        if (empty($itemsCountPerPage)) {
            return;
        }

        $this->itemsCountPerPage = $itemsCountPerPage;
    }

    public function delete($id, $entityObj = null): bool
    {
        try {
            if (empty($entityObj)) {
                $entityObj = $this->fetch($id);
            }
            $this->entityManager->remove($entityObj);
            $this->entityManager->flush($entityObj);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function fetch($id)
    {
        return $this->repository->find($id);
    }

    public function fetchByParamsEntityClass($entityClass, array $params)
    {
        return $this->entityManager->getRepository($entityClass)->findOneBy($params);
    }

    public function update($id, $data)
    {
        throw new Exception('Method not implemented', 400);
    }

    public function getReference($id)
    {
        if (empty($id)) {
            return null;
        }
        return $this->entityManager->getReference($this->entityName, $id);
    }

    /**
     * @return null|string
     */
    public function getLoggedUser(): ?string
    {
        if ($this->getIdentityService() !== null) {
            return $this->getIdentityService()->getIdentity();
        }
        return null;
    }

    /**
     * @param null|string $loggedUser
     */
    public function setLoggedUser($loggedUser): void
    {
        $this->loggedUser = $loggedUser;
    }

    /**
     * @return IdentityService|null
     */
    public function getIdentityService(): ?IdentityService
    {
        return $this->identityService;
    }

    /**
     * @param mixed $identityService
     */
    public function setIdentityService(IdentityService $identityService): void
    {
        $this->identityService = $identityService;
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }

    /**
     * @param array $additionalAttributes
     */
    public function setAdditionalAttributes(array $additionalAttributes): void
    {
        $this->additionalAttributes = $additionalAttributes;
    }
}
