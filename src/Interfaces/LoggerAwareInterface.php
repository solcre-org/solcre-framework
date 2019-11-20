<?php

namespace Solcre\SolcreFramework2\Interfaces;

use Psr\Log\LoggerInterface;

interface LoggerAwareInterface
{
    public function getLoggerService(): LoggerInterface;

    public function setLoggerService(LoggerInterface $logger): void;
}
