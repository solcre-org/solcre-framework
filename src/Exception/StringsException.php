<?php

namespace Solcre\SolcreFramework2\Exception;

class StringsException extends BaseException
{
    public static function openssl_cipher_iv_length_Exception(): self
    {
        return new self('openssl_cipher_iv_length method failure and return false.', 400);
    }

    public static function base64_decode_Exception(): self
    {
        return new self('base64_decode method failure and return false.', 400);
    }
}

