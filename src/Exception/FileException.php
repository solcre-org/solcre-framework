<?php

namespace Solcre\SolcreFramework2\Exception;

class FileException extends BaseException
{
    public static function tempnamException(): self
    {
        return new self('tempnam method failure and return false.', 400);
    }

    public static function createExtException(): self
    {
        return new self('strtolower method expect string, but null given.', 400);
    }
}

