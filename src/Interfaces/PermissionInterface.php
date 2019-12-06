<?php

namespace Solcre\SolcreFramework2\Interfaces;

use Exception;

interface PermissionInterface
{
    public function hasPermission(string $event, ?string $permissionName = null, $loggedUserId = null, $oauthType = null, bool $throwExceptions = true): bool;

    public function throwMethodNotAllowedForCurrentUserException(): Exception;
}
