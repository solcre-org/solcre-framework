<?php

namespace Solcre\SolcreFramework2\Service\Factory;

use Interop\Container\ContainerInterface;
use Solcre\SolcreFramework2\Service\ScheduleEmailService;
use Zend\ServiceManager\Factory\FactoryInterface;

class ScheduleEmailServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $doctrineService = $container->get('Doctrine\ORM\EntityManager');
        return new ScheduleEmailService($doctrineService);
    }
}
