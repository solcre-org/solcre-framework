<?php

namespace SolcreFrameworkTest\TestAsset\Factory;

use Interop\Container\ContainerInterface;
use SolcreFrameworkTest\TestAsset\DummyIdentityService;
use Zend\ServiceManager\Factory\FactoryInterface;

class DummyIdentityServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DummyIdentityService();
    }

}