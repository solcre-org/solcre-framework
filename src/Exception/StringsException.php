<?php

namespace Solcre\SolcreFramework2\Exception;

class StringsException extends BaseException
{
    public static function opensslCipherIvLengthException(): self
    {
        return new self('openssl_cipher_iv_length method failure and return false.', 400);
    }

    public static function base64DecodeException(): self
    {
        return new self('base64_decode method failure and return false.', 400);
    }
}
