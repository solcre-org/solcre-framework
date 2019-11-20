<?php

namespace Solcre\SolcreFramework2\Interfaces;

interface IdentityAwareInterface
{
    public function getIdentityService(): IdentityInterface;

    public function setIdentityService(IdentityInterface $identityService): void;
}
