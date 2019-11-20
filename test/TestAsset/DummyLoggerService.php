<?php

namespace SolcreFrameworkTest\TestAsset;

use Psr\Log\LoggerInterface;

class DummyLoggerService implements LoggerInterface
{
    public function alert($message, array $context = [])
    {
        // TODO: Implement alert() method.
    }

    public function critical($message, array $context = [])
    {
        // TODO: Implement critical() method.
    }

    public function debug($message, array $context = [])
    {
        // TODO: Implement debug() method.
    }

    public function emergency($message, array $context = [])
    {
        // TODO: Implement emergency() method.
    }

    public function error($message, array $context = [])
    {
        // TODO: Implement error() method.
    }

    public function info($message, array $context = [])
    {
        // TODO: Implement info() method.
    }

    public function log($level, $message, array $context = [])
    {
        // TODO: Implement log() method.
    }

    public function notice($message, array $context = [])
    {
        // TODO: Implement notice() method.
    }

    public function warning($message, array $context = [])
    {
        // TODO: Implement warning() method.
    }
}
