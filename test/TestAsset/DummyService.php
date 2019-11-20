<?php

namespace SolcreFrameworkTest\TestAsset;

use Psr\Log\LoggerInterface;
use Solcre\SolcreFramework2\Interfaces\IdentityAwareInterface;
use Solcre\SolcreFramework2\Interfaces\IdentityInterface;
use Solcre\SolcreFramework2\Interfaces\LoggerAwareInterface;
use Solcre\SolcreFramework2\Interfaces\PermissionAwareInterface;
use Solcre\SolcreFramework2\Interfaces\PermissionInterface;
use Solcre\SolcreFramework2\Service\BaseService;

class DummyService extends BaseService implements IdentityAwareInterface, PermissionAwareInterface, LoggerAwareInterface
{
    private $identityService;
    private $permissionService;
    private $loggerService;

    public function getIdentityService(): IdentityInterface
    {
        return $this->identityService;
    }

    public function setIdentityService(IdentityInterface $identityService): void
    {
        $this->identityService = $identityService;
    }

    public function getLoggerService(): LoggerInterface
    {
        return $this->loggerService;
    }

    public function setLoggerService(LoggerInterface $logger): void
    {
        $this->loggerService = $logger;
    }

    public function getPermissionService(): PermissionInterface
    {
        return $this->permissionService;
    }

    public function setPermissionService(PermissionInterface $permissionService): void
    {
        $this->permissionService = $permissionService;
    }
}
