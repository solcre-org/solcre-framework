<?php

namespace Solcre\SolcreFramework2\Service\Factory;

use Interop\Container\ContainerInterface;
use MegaPharma\V1\Domain\Common\Service\TwigService;
use PHPMailer\PHPMailer\PHPMailer;
use Solcre\SolcreFramework2\Service\EmailService;
use Solcre\SolcreFramework2\Service\ScheduleEmailService;
use Zend\ServiceManager\Factory\FactoryInterface;

class EmailServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mailer = new PHPMailer();
        $configuration = $container->get('config');
        $scheduleEmailService = $container->get(ScheduleEmailService::class);
        $twigService = $container->get(TwigService::class);
        $logger = $container->get('EmailLogger');
        return new EmailService($mailer, $configuration, $scheduleEmailService, $twigService, $logger);
    }
}
