<?php

namespace SolcreFrameworkTest\TestAsset\Factory;

use Interop\Container\ContainerInterface;
use SolcreFrameworkTest\TestAsset\DummyPermissionService;
use Zend\ServiceManager\Factory\FactoryInterface;

class DummyPermissionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DummyPermissionService();
    }
}
