<?php

namespace SolcreFrameworkTest\TestAsset;

use Exception;
use Solcre\SolcreFramework2\Interfaces\PermissionInterface;

class DummyPermissionWithDependencyService implements PermissionInterface
{
    private $dummyDependency;


    /**
     * DummyPermissionService constructor.
     * @param $dummyDependency
     */
    public function __construct($dummyDependency)
    {
        $this->dummyDependency = $dummyDependency;
    }

    /**
     * @return mixed
     */
    public function getDummyDependency()
    {
        return $this->dummyDependency;
    }

    public function hasPermission(string $event, ?string $permissionName = null, $loggedUserId = null, $oauthType = null, bool $throwExceptions = true): bool
    {
        // TODO: Implement hasPermission() method.
    }

    public function throwMethodNotAllowedForCurrentUserException(): Exception
    {
        // TODO: Implement throwMethodNotAllowedForCurrentUserException() method.
    }
}
