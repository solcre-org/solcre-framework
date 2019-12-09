<?php

namespace SolcreFrameworkTest\TestAsset;

use Solcre\SolcreFramework2\Interfaces\IdentityInterface;

class DummyIdentityService implements IdentityInterface
{
    public function getIdentityService(): string
    {
        // TODO: Implement getIdentityService() method.
    }

    public function getOauthType(): ?int
    {
        // TODO: Implement getOauthType() method.
    }

    public function getUserId(): ?int
    {
        // TODO: Implement getUserId() method.
    }

    public function setIdentityService(string $identity): void
    {
        // TODO: Implement setIdentityService() method.
    }

    public function setOauthType(?int $oauthType): void
    {
        // TODO: Implement setOauthType() method.
    }

    public function setUserId(?int $userId): void
    {
        // TODO: Implement setUserId() method.
    }
}
