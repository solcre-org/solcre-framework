<?php

namespace Solcre\SolcreFramework2\Hydrator\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;

class EntityHydratorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $doctrineService = $container->get(EntityManager::class);
        return new EntityHydrator($doctrineService);
    }
}
