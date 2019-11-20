<?php

namespace SolcreFrameworkTest\TestAsset\Factory;

use Interop\Container\ContainerInterface;
use SolcreFrameworkTest\TestAsset\DummyDependencyService;
use Zend\ServiceManager\Factory\FactoryInterface;

class DummyDependencyServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DummyDependencyService();
    }
}
