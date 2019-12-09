<?php

namespace Solcre\SolcreFramework2\Interfaces;

interface IdentityInterface
{
    public function getIdentityService(): string;

    public function setIdentityService(string $identity): void;

    public function getUserId(): ?int;

    public function setUserId(?int $userId): void;

    public function getOauthType(): ?int;

    public function setOauthType(?int $oauthType): void;
}
