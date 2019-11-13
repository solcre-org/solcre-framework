<?php

namespace Solcre\SolcreFramework2\Exception;

class ZipException extends BaseException
{
    public static function temporaryFilenameException(): self
    {
        return new self('Error when is created a temporary filename', 400);
    }

    public static function nameOfIndexException(): self
    {
        return new self('Failure to get name of index', 400);
    }

    public static function invalidNameException(): self
    {
        return new self('Invalid Name', 400);
    }
}
