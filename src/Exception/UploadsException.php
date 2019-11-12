<?php

namespace Solcre\SolcreFramework2\Exception;

class UploadsException extends BaseException
{
    public static function invalidNameException(): self
    {
        return new self('Invalid Name', 400);
    }
}
