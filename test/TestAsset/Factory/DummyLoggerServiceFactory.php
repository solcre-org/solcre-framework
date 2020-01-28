<?php

namespace SolcreFrameworkTest\TestAsset\Factory;

use Interop\Container\ContainerInterface;
use SolcreFrameworkTest\TestAsset\DummyLoggerService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DummyLoggerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DummyLoggerService();
    }
}
