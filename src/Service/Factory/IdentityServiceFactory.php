<?php

namespace Solcre\SolcreFramework2\Service\Factory;

use Interop\Container\ContainerInterface;
use Solcre\SolcreFramework2\Service\IdentityService;
use Zend\ServiceManager\Factory\FactoryInterface;

class IdentityServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IdentityService();
    }
}
