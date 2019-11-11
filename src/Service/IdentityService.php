<?php

namespace Solcre\SolcreFramework2\Service;

class IdentityService
{
    private $identity;

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     */
    public function setIdentity(string $identity): void
    {
        $this->identity = $identity;
    }
}
