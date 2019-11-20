<?php

namespace SolcreFrameworkTest\TestAsset;

use Exception;
use Solcre\SolcreFramework2\Interfaces\PermissionInterface;

class DummyPermissionService implements PermissionInterface
{
    public function hasPermission(string $event, ?string $permissionName = null, bool $throwExceptions = true): bool
    {
        // TODO: Implement hasPermission() method.
    }

    public function throwMethodNotAllowedForCurrentUserException(): Exception
    {
        // TODO: Implement throwMethodNotAllowedForCurrentUserException() method.
    }
}