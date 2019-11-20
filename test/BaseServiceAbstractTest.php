<?php

namespace SolcreFrameworkTest;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Solcre\SolcreFramework2\AbstractFactory\BaseServiceAbstractFactory;
use Solcre\SolcreFramework2\Filter\FieldsFilterService;
use SolcreFrameworkTest\TestAsset;
use SolcreFrameworkTest\TestAsset\Factory;
use Zend\ServiceManager\ServiceManager;

class BaseServiceAbstractTest extends TestCase
{
    public function testServiceHasInjectedTheDesiresServices(): void
    {
        $applicationConfig = [
            'interfaces_classes' => [
                BaseServiceAbstractFactory::PERMISSION_SERVICE_CLASS => TestAsset\DummyPermissionService::class,
                BaseServiceAbstractFactory::LOGGER_SERVICE_CLASS     => TestAsset\DummyLoggerService::class,
                BaseServiceAbstractFactory::IDENTITY_SERVICE_CLASS   => TestAsset\DummyIdentityService::class
            ]
        ];

        $config = [
            'factories'          => [
                TestAsset\DummyService::class           => BaseServiceAbstractFactory::class,
                TestAsset\DummyLoggerService::class     => Factory\DummyLoggerServiceFactory::class,
                TestAsset\DummyPermissionService::class => Factory\DummyPermissionServiceFactory::class,
                TestAsset\DummyIdentityService::class   => Factory\DummyIdentityServiceFactory::class
            ],
            'abstract_factories' => [
                BaseServiceAbstractFactory::class,
            ]
        ];

        $serviceManager = $this->createServiceManager($config, $applicationConfig);

        $abstractFactory = new BaseServiceAbstractFactory();

        self::assertTrue($abstractFactory->canCreate($serviceManager, TestAsset\DummyService::class));

        $service = $serviceManager->get(TestAsset\DummyService::class);

        self::assertInstanceOf(TestAsset\DummyPermissionService::class, $service->getPermissionService());
        self::assertInstanceOf(TestAsset\DummyLoggerService::class, $service->getLoggerService());
        self::assertInstanceOf(TestAsset\DummyIdentityService::class, $service->getIdentityService());
    }

    private function createServiceManager(array $config, array $applicationConfig): ServiceManager
    {
        $serviceManager = new ServiceManager($config);

        $serviceManager->setService('config', $applicationConfig);

        $serviceManager->setService(EntityManager::class, $this->createMock(EntityManager::class));
        $serviceManager->setService(FieldsFilterService::class, $this->createMock(FieldsFilterService::class));

        return $serviceManager;
    }

    public function testServiceHasInjectedTheDesiresServicesWithDependencies(): void
    {
        $applicationConfig = [
            'interfaces_classes' => [
                BaseServiceAbstractFactory::PERMISSION_SERVICE_CLASS => TestAsset\DummyPermissionWithDependencyService::class,
            ]
        ];

        $config = [
            'factories'          => [
                TestAsset\DummyService::class                         => BaseServiceAbstractFactory::class,
                TestAsset\DummyPermissionWithDependencyService::class => Factory\DummyPermissionWithDependencyServiceFactory::class,
                TestAsset\DummyDependencyService::class               => Factory\DummyDependencyServiceFactory::class,
            ],
            'abstract_factories' => [
                BaseServiceAbstractFactory::class,
            ]
        ];

        $serviceManager = $this->createServiceManager($config, $applicationConfig);

        $service = $serviceManager->get(TestAsset\DummyService::class);

        self::assertInstanceOf(TestAsset\DummyDependencyService::class, $service->getPermissionService()->getDummyDependency());
    }
}
