<?php

namespace SolcreFrameworkTest;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Solcre\SolcreFramework2\Common\BaseRepository;
use Solcre\SolcreFramework2\Entity\PaginatedResult;
use Solcre\SolcreFramework2\Exception\BaseException;
use Solcre\SolcreFramework2\Filter\FilterInterface;
use Solcre\SolcreFramework2\Service\BaseService;
use Doctrine\ORM\Query\FilterCollection;

class BaseServiceTest extends TestCase
{
    protected $mockedEntityManager;
    protected $mockedRepository;
    protected $baseService;

    public function setUp(): void
    {
        $this->mockedEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getRepository',
                    'getFilters',
                    'getUnitOfWork',
                    'persist',
                    'flush',
                    'remove',
                    'getReference'
                ]
            )
            ->getMock();

        $this->mockedRepository = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'addFilter',
                    'findOneBy',
                    'findAll',
                    'findBy',
                    'findByPaginated',
                    'find'
                ]
            )
            ->getMock();

        $this->mockedEntityManager->method('getRepository')->willReturn($this->mockedRepository);

        $this->baseService = new class($this->mockedEntityManager) extends BaseService
        {
        };
    }

    public function testEnableFilter(): void
    {
        $mockFilterCollection = $this->createMock(FilterCollection::class);
        $mockFilterCollection->method('isEnabled')->willReturn(false);
        $mockFilterCollection->method('enable')->willReturn(false);

        $this->mockedEntityManager->method('getFilters')->willReturn($mockFilterCollection);

        $filterName = 'filterName';

        $mockFilterCollection->expects($this->once())
           ->method('enable');

        $this->baseService->enableFilter($filterName);
    }

    public function testEnableFilterWithNull(): void
    {
        $mockFilterCollection = $this->createMock(FilterCollection::class);
        $mockFilterCollection->method('isEnabled')->willReturn(false);
        $mockFilterCollection->method('enable')->willReturn(false);

        $this->mockedEntityManager->method('getFilters')->willReturn($mockFilterCollection);

        $filterName = null;

        $mockFilterCollection->expects($this->once())
            ->method('enable');

        $this->baseService->enableFilter($filterName);
    }

    public function testDisableFilter(): void
    {
        $filterName = 'filterName';

        $mockFilterCollection = $this->createMock(FilterCollection::class);
        $mockFilterCollection->method('isEnabled')->willReturn(true);

        $this->mockedEntityManager->method('getFilters')->willReturn($mockFilterCollection);

        $mockFilterCollection->expects($this->once())
            ->method('disable');

        $this->baseService->disableFilter($filterName);
    }

    public function testDisableFilterWithNull(): void
    {
        $filterName = null;

        $mockFilterCollection = $this->createMock(FilterCollection::class);
        $mockFilterCollection->method('isEnabled')->willReturn(true);

        $this->mockedEntityManager->method('getFilters')->willReturn($mockFilterCollection);

        $mockFilterCollection->expects($this->once())
            ->method('disable');

        $this->baseService->disableFilter($filterName);
    }

    public function testIsEntityPersisted(): void
    {
         $mockUnitOfWork = $this->createMock(UnitOfWork::class);
         $mockUnitOfWork->method('isEntityScheduled')->willReturn(true);

         $this->mockedEntityManager->method('getUnitOfWork')->willReturn($mockUnitOfWork);

         $entityObject = (object)['prop1' => 'value1'];

         $mockUnitOfWork->expects($this->once())
            ->method('isEntityScheduled');

         $this->baseService->isEntityPersisted($entityObject);
    }

    public function testIsEntityPersistedIsTrue(): void
    {
        $mockUnitOfWork = $this->createMock(UnitOfWork::class);
        $mockUnitOfWork->method('isEntityScheduled')->willReturn(true);

        $this->mockedEntityManager->method('getUnitOfWork')->willReturn($mockUnitOfWork);

        $entityObject = (object)['prop1' => 'value1'];

        $this->assertTrue($this->baseService->isEntityPersisted($entityObject));
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

    public function setupBaseServiceForAddAndDelete(): MockObject
    {
        $mockedBaseService = $this->getMockBuilder(BaseService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepareEntity', 'fetch'])
            ->getMock();

        $expectedEntityObject = (object)['prop1' => 'value1'];
        $mockedBaseService->method('fetch')->willReturn($expectedEntityObject);
        $mockedBaseService->method('prepareEntity')->willReturn($expectedEntityObject);
        $mockedBaseService->__construct($this->mockedEntityManager);

        return $mockedBaseService;
    }

    public function testAdd(): void
    {
        $mockedBaseService = $this->setupBaseServiceForAddAndDelete();

        $data         = ['data'];
        $entityObject = (object)['prop1' => 'value1'];

        $this->mockedEntityManager->expects($this->once())
            ->method('persist')
            ->with(
                $this->equalTo($entityObject)
            );

        $this->mockedEntityManager->expects($this->once())
            ->method('flush');

        $mockedBaseService->expects($this->once())
            ->method('prepareEntity');

        $mockedBaseService->add($data);
    }

    public function testFetchOne(): void
    {
        $this->mockedRepository->method('findOneBy')->willReturn(['return of repository findOneBy method']);

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
        $this->mockedRepository->method('findOneBy')->willReturn(['return of repository findOneBy method']);

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
        $this->mockedRepository->method('findAll')->willReturn(['return of repository findAll method']);

        $params         = [];
        $expectedReturn = ['return of repository findAll method'];

        $this->assertEquals($this->baseService->fetchAll($params), $expectedReturn);
    }

    public function testFetchAllWithParams(): void
    {
        $this->mockedRepository->method('findBy')->willReturn(['return of repository findBy method']);

        $params         = ['params'];
        $expectedReturn = ['return of repository findBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findBy');

        $this->assertEquals($this->baseService->fetchAll($params), $expectedReturn);
    }

    public function testFetchAllWithOrder(): void
    {
        $this->mockedRepository->method('findBy')->willReturn(['return of repository findBy method']);

        $params         = [];
        $order          = ['order'];
        $expectedReturn = ['return of repository findBy method'];

        $this->mockedRepository->expects($this->once())
            ->method('findBy');

        $this->assertEquals($this->baseService->fetchAll($params, $order), $expectedReturn);
    }

    public function setupFetchAllPaginated(): MockObject
    {
        $mockedBaseService = $this->getMockBuilder(BaseService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['paginateResults'])
            ->getMock();

        $retPaginatedResult = $this->createMock(PaginatedResult::class);
        $mockedBaseService->method('paginateResults')->willReturn($retPaginatedResult);

        $doctrinePaginatorObject = $this->createMock(DoctrinePaginator::class);
        $this->mockedRepository->method('findByPaginated')->willReturn($doctrinePaginatorObject);

        $mockedBaseService->__construct($this->mockedEntityManager);

        return $mockedBaseService;
    }

    public function testFetchAllPaginated(): void
    {
        $mockedBaseService = $this->setupFetchAllPaginated();

        $params  = ['params'];
        $orderBy = ['orderBy'];

        $this->mockedRepository->expects($this->once())
            ->method('findByPaginated')
            ->with($params, $orderBy);

        $mockedBaseService->expects($this->once())
            ->method('paginateResults');

        $mockedBaseService->fetchAllPaginated($params, $orderBy);
    }

    public function testGetCurrentPage(): void
    {
        $this->assertTrue(gettype($this->baseService->getCurrentPage()) === 'integer');
    }

    public function testGetItemsCountPerPage(): void
    {
        $this->assertTrue(gettype($this->baseService->getItemsCountPerPage()) === 'integer');
    }

    public function testDeleteWithEntityObjetc(): void
    {
        $entityObj =  (object)['prop1' => 'value1'];
        $id        = 12345;

        $this->mockedEntityManager->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->mockedEntityManager->expects($this->once())
            ->method('flush')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->assertTrue($this->baseService->delete($id, $entityObj));
    }

    public function testDeleteWithoutEntityObject(): void
    {
        $mockedBaseService = $this->setupBaseServiceForAddAndDelete();
        $entityObj = (object)['prop1' => 'value1'];
        $id        = 12345;

        $mockedBaseService->expects($this->once())
            ->method('fetch')
            ->with(
                $this->equalTo($id)
            );

        $this->mockedEntityManager->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->mockedEntityManager->expects($this->once())
            ->method('flush')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->assertTrue($mockedBaseService->delete($id));
    }

    public function testDeleteWithWithRemoveException(): void
    {
        $entityObj =  (object)['prop1' => 'value1'];
        $id        = '12345';

        $this->mockedEntityManager->method('remove')->will($this->throwException(
            new \Exception()
        ));

        $this->mockedEntityManager->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($entityObj)
            );

        $this->expectException(\Exception::class);

        $this->baseService->delete($id, $entityObj);
    }

    public function testFetch(): void
    {
        $this->mockedRepository->method('find')->willReturn(['return of repository find method']);

        $id             = 12345;
        $expectedReturn = ['return of repository find method'];

        $this->mockedRepository->expects($this->once())
            ->method('find');

        $this->assertEquals($this->baseService->fetch($id), $expectedReturn);
    }

    public function testFetchByParamsEntityClass(): void
    {
        $this->mockedRepository->method('findOneBy')->willReturn(['return of repository findOneBy method']);
        $expectedReturn = ['return of repository findOneBy method'];

        $entityName = 'entityName';
        $params     = [
            'param1' => 'value1',
            'param2' => 'value2'
        ];

        $this->mockedEntityManager->expects($this->once())
            ->method('getRepository')
            ->with(
                $this->equalTo($entityName)
            );

        $this->mockedRepository->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo($params)
            );

        $this->assertEquals($this->baseService->fetchByParamsEntityClass($entityName, $params), $expectedReturn);
    }

    public function testUpdate(): void
    {
        $id   = '12345';
        $data = [];

        $this->expectException(BaseException::class);

        $this->baseService->update($id, $data);
    }

    public function testGetReferenceWithNull(): void
    {
        $this->assertNull($this->baseService->getReference(null));
    }

    public function testGetReference(): void
    {
        $id = 12345;

        $this->mockedEntityManager->expects($this->once())
            ->method('getReference');

        $this->baseService->getReference($id);
    }
}
