<?php

namespace Solcre\SolcreFramework2\Exception;

class ArraysException extends BaseException
{
    public static function nonCountableException(): self
    {
        return new self('count expected array|Countable $var, but null given.', 400);
    }
}
