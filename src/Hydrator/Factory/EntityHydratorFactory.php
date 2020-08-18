<?php

namespace Solcre\SolcreFramework2\Hydrator\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;

class EntityHydratorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $doctrineService = $container->get(EntityManager::class);
        return new EntityHydrator($doctrineService);
    }
}
