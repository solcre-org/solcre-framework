<?php

namespace Solcre\SolcreFramework2\Interfaces;

interface PermissionAwareInterface
{
    public function getPermissionService(): PermissionInterface;

    public function setPermissionService(PermissionInterface $permissionService): void;
}