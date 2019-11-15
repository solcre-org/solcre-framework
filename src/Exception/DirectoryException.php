<?php

namespace Solcre\SolcreFramework2\Exception;

class DirectoryException extends BaseException
{
    public static function scandirException(): self
    {
        return new self('scandir method failure', 400);
    }

    public static function filesizeException(): self
    {
        return new self('filesize method failure', 400);
    }

    public static function globPathException(): self
    {
        return new self('glob method failure', 400);
    }

    public static function fileTimeException(): self
    {
        return new self('filetime method failure', 400);
    }
}
