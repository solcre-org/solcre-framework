<?php

namespace SolcreFrameworkTest;

use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Solcre\SolcreFramework2\Common\BaseRepository;
use Solcre\SolcreFramework2\Filter\FilterInterface;
use Solcre\SolcreFramework2\Service\BaseService;

class BaseServiceTest extends TestCase
{
    protected $mockedEntityManager;
    protected $mockedRepository;
    protected $entityName;
    protected $currentPage = 1;
    protected $itemsCountPerPage = 50;
    protected $configuration;
    protected $identityService;
    protected $loggedUser;
    protected $baseService;

    public function setUp(): void
    {
        $this->mockedEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getRepository',
                    'getFilters',
                    'enable',
                    'isEnabled',
                    'disable',
                    'getUnitOfWork',
                    'isEntityScheduled',
                    'persist',
                    'flush'
                ]
            )
            ->getMock();

        $this->mockedRepository = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'addFilter',
                    'findOneBy',
                    'findAll',
                    'findBy',
                    'findByPaginated'
                ]
            )
            ->getMock();

        $this->mockedRepository->method('findOneBy')->willReturn(['return of repository findOneBy method']);
        $this->mockedRepository->method('findAll')->willReturn(['return of repository findAll method']);
        $this->mockedRepository->method('findBy')->willReturn(['return of repository findBy method']);

        $returnOfDoctrinePaginator = $this->createMock(DoctrinePaginator::class);
        $this->mockedRepository->method('findByPaginated')->willReturn($returnOfDoctrinePaginator);

        $this->mockedEntityManager->method('getRepository')->willReturn($this->mockedRepository);
        $this->mockedEntityManager->method('getFilters')->willReturn($this->mockedEntityManager);
        $this->mockedEntityManager->method('enable')->willReturn(true);
        $this->mockedEntityManager->method('isEnabled')->willReturn(true);
        $this->mockedEntityManager->method('disable')->willReturn(true);
        $this->mockedEntityManager->method('getUnitOfWork')->willReturn($this->mockedEntityManager);
        $this->mockedEntityManager->method('isEntityScheduled')->willReturn(true);

        $this->entityName = 'entity name';

        // $this->additionalAttributes = [];

        $this->baseService = new class($this->mockedEntityManager) extends BaseService
        {
        };
    }

//    public function testEnableFilter(): void
//    {
//        $this->mockedEntityManager->expects($this->once())
//            ->method('getFilters');
//
//        $this->mockedEntityManager->expects($this->once())
//            ->method('enable');
//
//        $this->baseService->enableFilter(null);
//    }

    public function testDisableFilterWithIsEnabledFilter(): void
    {
        $this->mockedEntityManager->expects($this->once())
            ->method('getFilters');

        $this->mockedEntityManager->expects($this->once())
            ->method('disable');

        $this->baseService->disableFilter(null);
    }

    public function testIsEntityPersisted(): void
    {
        $entity = 'entity';

        $this->mockedEntityManager->expects($this->once())
            ->method('isEntityScheduled')
            ->with(
                $this->equalTo($entity)
            );

        $this->baseService->isEntityPersisted($entity);
    }

    public function testIsEntityPersistedIsTrue(): void
    {
        $entity = 'entity';

        $this->assertTrue($this->baseService->isEntityPersisted($entity));
    }

    public function testGetEntityManager(): void
    {
        $this->assertEquals($this->baseService->getEntityManager(), $this->mockedEntityManager);
    }

    public function testGetRepository(): void
    {
        $this->assertEquals($this->baseService->getRepository(), $this->mockedRepository);
    }

    public function testAddFilter(): void
    {
        $filter = $this->createMock(FilterInterface::class);

        $this->mockedRepository->expects($this->once())
            ->method('addFilter')
            ->with(
                $this->equalTo($filter)
            );

        $this->baseService->addFilter($filter);
    }

    public function setupBaseServiceForAddMethod(): MockObject
    {
        $mockedBaseService = $this->getMockBuilder(BaseService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepareEntity'])
            ->getMock();

        $entityObject = 'entity object';
        $mockedBaseService->method('prepareEntity')->willReturn($entityObject);
        $mockedBaseService->__construct($this->mockedEntityManager);

        return $mockedBaseService;
    }

    public function testAdd(): void
    {
        $mockedBaseService = $this->setupBaseServiceForAddMethod();

        $data = ['data'];
        $entityObj = 'entity object';

        $this->mockedEntityManager->expects($this->once())
            ->method('persist')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->mockedEntityManager->expects($this->once())
            ->method('flush');

        $mockedBaseService->expects($this->once())
            ->method('prepareEntity');

        $mockedBaseService->add($data);
    }

    public function testFetchOne(): void
    {
        $id = 1;

        $params = [
            'id' => $id
        ];

        $expectedReturn = ['return of repository findOneBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo($params)
            );

        $this->assertEquals($this->baseService->fetchOne($id, $params), $expectedReturn);
    }

    public function testFetchBy(): void
    {
        $params = [
            'id' => 1
        ];

        $expectedReturn = ['return of repository findOneBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo($params)
            );

        $this->assertEquals($this->baseService->fetchBy($params), $expectedReturn);
    }

    public function testFetchAllWithoutParams(): void
    {
        $params = [];

        $expectedReturn = ['return of repository findAll method'];

        $this->assertEquals($this->baseService->fetchAll($params), $expectedReturn);
    }

    public function testFetchAllWithParams(): void
    {
        $params = ['params'];
        $expectedReturn = ['return of repository findBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findBy');

        $this->assertEquals($this->baseService->fetchAll($params), $expectedReturn);
    }

    public function testFetchAllWithOrder(): void
    {
        $params = [];
        $order = ['order'];
        $expectedReturn = ['return of repository findBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findBy');

        $this->assertEquals($this->baseService->fetchAll($params, $order), $expectedReturn);
    }
}
