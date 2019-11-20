<?php

namespace Solcre\SolcreFramework2\AbstractFactory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Solcre\SolcreFramework2\Filter\FieldsFilterService;
use Solcre\SolcreFramework2\Interfaces\IdentityAwareInterface;
use Solcre\SolcreFramework2\Interfaces\LoggerAwareInterface;
use Solcre\SolcreFramework2\Interfaces\PermissionAwareInterface;
use Solcre\SolcreFramework2\Service\BaseService;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use function is_subclass_of;

class BaseServiceAbstractFactory implements AbstractFactoryInterface
{

    public const PERMISSION_SERVICE_CLASS = 'PermissionServiceClass';
    public const LOGGER_SERVICE_CLASS = 'LoggerServiceClass';
    public const IDENTITY_SERVICE_CLASS = 'IdentityServiceClass';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);

        $service = new $requestedName($entityManager);

        $fieldsFilter = $container->get(FieldsFilterService::class);
        $service->addFilter($fieldsFilter);

        $interfacesClasses = $container->get('config')['interfaces_classes'];

        $permissionServiceClass = $interfacesClasses[self::PERMISSION_SERVICE_CLASS] ?? null;
        if ($permissionServiceClass !== null && is_subclass_of($service, PermissionAwareInterface::class) && $container->has($permissionServiceClass)) {
            $service->setPermissionService($container->get($permissionServiceClass));
        }

        $loggerServiceClass = $interfacesClasses[self::LOGGER_SERVICE_CLASS] ?? null;
        if ($loggerServiceClass !== null && is_subclass_of($service, LoggerAwareInterface::class) && $container->has($loggerServiceClass)) {
            $service->setLoggerService($container->get($loggerServiceClass));
        }

        $identityServiceClass = $interfacesClasses[self::IDENTITY_SERVICE_CLASS] ?? null;
        if ($identityServiceClass !== null && is_subclass_of($service, IdentityAwareInterface::class) && $container->has($identityServiceClass)) {
            $service->setIdentityService($container->get($identityServiceClass));
        }

        return $service;
    }

    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        return $container->has(EntityManager::class) && is_subclass_of($requestedName, BaseService::class);
    }
}
