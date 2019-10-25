<?php

namespace Solcre\SolcreFramework2\Service\Factory;

use Interop\Container\ContainerInterface;
use Solcre\SolcreFramework2\Service\EmailService;
use Solcre\SolcreFramework2\Service\ScheduleEmailService;
use Solcre\SolcreFramework2\Service\SendScheduleEmailService;
use Zend\ServiceManager\Factory\FactoryInterface;

class SendScheduleEmailServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $doctrineService = $container->get('Doctrine\ORM\EntityManager');
        $scheduleEmailService = $container->get(ScheduleEmailService::class);
        $emailService = $container->get(EmailService::class);
        $logger = $container->get('EmailLogger');
        return new SendScheduleEmailService($doctrineService, $scheduleEmailService, $emailService, $logger);
    }
}
