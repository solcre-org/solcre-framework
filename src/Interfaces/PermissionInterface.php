<?php

namespace Solcre\SolcreFramework2\Interfaces;

use Exception;

interface PermissionInterface
{
    public function hasPermission(string $event, ?string $permissionName = null, bool $throwExceptions = true, $loggedUserId = null, $oauthType = null): bool;

    public function throwMethodNotAllowedForCurrentUserException(): Exception;
}
