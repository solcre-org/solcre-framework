<?php

namespace Solcre\SolcreFramework2\Interfaces;

interface IdentityInterface
{
    public function getIdentityService(): string;

    public function setIdentityService(string $identity): void;
}
