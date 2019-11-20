<?php

namespace SolcreFrameworkTest\TestAsset\Factory;

use Interop\Container\ContainerInterface;
use SolcreFrameworkTest\TestAsset\DummyDependencyService;
use SolcreFrameworkTest\TestAsset\DummyPermissionWithDependencyService;
use Zend\ServiceManager\Factory\FactoryInterface;

class DummyPermissionWithDependencyServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dummyDependency = $container->get(DummyDependencyService::class);
        return new DummyPermissionWithDependencyService($dummyDependency);
    }

}