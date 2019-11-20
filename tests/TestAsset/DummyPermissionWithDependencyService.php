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

    public function hasPermission(string $event, ?string $permissionName = null, bool $throwExceptions = true): bool
    {
        // TODO: Implement hasPermission() method.
    }

    public function throwMethodNotAllowedForCurrentUserException(): Exception
    {
        // TODO: Implement throwMethodNotAllowedForCurrentUserException() method.
    }

    /**
     * @return mixed
     */
    public function getDummyDependency()
    {
        return $this->dummyDependency;
    }


}