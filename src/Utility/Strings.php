<?php

namespace Solcre\SolcreFramework2\Utility;

use ForceUTF8\Encoding;
use Solcre\SolcreFramework2\Exception\StringsException;

class Strings
{
    const VALIDATE_KEY = 'COLUMNIS';
    const ENCRYPTION_METHOD = 'AES-256-CBC';
    const ENCRYPTION_GLUE = '::';

    public static function bcryptPassword($password, $cost = 10)
    {
        $salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
        $hash = crypt($password, '$2a$' . $cost . '$' . $salt);

        return $hash;
    }

    public static function verifyBcryptPassword($password, $existingBscrypt)
    {
        $hash = crypt($password, $existingBscrypt);

        return ($hash === $existingBscrypt);
    }

    public static function isBcryptPassword($bscryptPassword)
    {
        return (strlen($bscryptPassword) === 60);
    }

    public static function generateRandomPassword($lenght)
    {
        $alphabet    = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!#$%&/()?~[]";
        $pass        = []; //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

        for ($i = 0; $i < $lenght; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    public static function generateAlphaNumericString($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string     = '';
        $max        = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return strtoupper($string);
    }

    /**
     * Returns a default value if $variable is empty
     *
     * @param string $variable The variable to check if empty.
     * @param string $default The default value if empty.
     *
     * @return String
     */
    public static function defaultText($variable, $default): string
    {
        return empty($variable) ? $default : $variable;
    }

    public static function validateRut($rut)
    {
        $rut = strlen((string)$rut);

        if ($rut !== 12) {
            throw new \InvalidArgumentException('Rut param incorrect formatted', 422);
        }

        return true;
    }

    /**
     * Returns a md5 hash
     *
     * @param string $optionString
     *
     * @return string
     */
    public static function generateMd5Hash($optionString = '')
    {
        return md5(self::VALIDATE_KEY . $optionString);
    }

    /**
     * Validate md5 hash
     *
     * @param string $hash
     * @param mixed $comparedString
     *
     * @return boolean
     */
    public static function validateMd5Hash($hash, $comparedString)
    {
        return md5(self::VALIDATE_KEY . $comparedString) == $hash;
    }

    public static function cleanName($text)
    {
        $space_char          = '-';
        $text                = self::strtolowerUtf8(trim(Encoding::toUTF8($text)));
        $code_entities_match = [
            '¿',
            'ñ',
            'á',
            'é',
            'í',
            'ó',
            'ú',
            ' ',
            '--',
            '&quot;',
            '!',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '+',
            '{',
            '}',
            '|',
            ':',
            '"',
            '<',
            '>',
            '?',
            '[',
            ']',
            '\\',
            ';',
            "'",
            ',',
            '/',
            '*',
            '+',
            '~',
            '`',
            '=',
            '°',
            '´'
        ];
        $code_entities_replace = [
            '',
            'n',
            'a',
            'e',
            'i',
            'o',
            'u',
            $space_char,
            '_',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $text = str_replace($code_entities_match, $code_entities_replace, $text);

        return str_replace('__', '_', $text);
    }

    public static function strtolowerUtf8($string)
    {
        $string     = trim(Encoding::toUTF8($string));
        $convert_to = [
            "a",
            "b",
            "c",
            "d",
            "e",
            "f",
            "g",
            "h",
            "i",
            "j",
            "k",
            "l",
            "m",
            "n",
            "o",
            "p",
            "q",
            "r",
            "s",
            "t",
            "u",
            "v",
            "w",
            "x",
            "y",
            "z",
            "à",
            "á",
            "â",
            "ã",
            "ä",
            "å",
            "æ",
            "ç",
            "è",
            "é",
            "ê",
            "ë",
            "ì",
            "í",
            "î",
            "ï",
            "ð",
            "ñ",
            "ò",
            "ó",
            "ô",
            "õ",
            "ö",
            "ø",
            "ù",
            "ú",
            "û",
            "ü",
            "ý",
            "а",
            "б",
            "в",
            "г",
            "д",
            "е",
            "ё",
            "ж",
            "з",
            "и",
            "й",
            "к",
            "л",
            "м",
            "н",
            "о",
            "п",
            "р",
            "с",
            "т",
            "у",
            "ф",
            "х",
            "ц",
            "ч",
            "ш",
            "щ",
            "ъ",
            "ы",
            "ь",
            "э",
            "ю",
            "я"
        ];
        $convert_from = [
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "U",
            "V",
            "W",
            "X",
            "Y",
            "Z",
            "À",
            "Á",
            "Â",
            "Ã",
            "Ä",
            "Å",
            "Æ",
            "Ç",
            "È",
            "É",
            "Ê",
            "Ë",
            "Ì",
            "Í",
            "Î",
            "Ï",
            "Ð",
            "Ñ",
            "Ò",
            "Ó",
            "Ô",
            "Õ",
            "Ö",
            "Ø",
            "Ù",
            "Ú",
            "Û",
            "Ü",
            "Ý",
            "А",
            "Б",
            "В",
            "Г",
            "Д",
            "Е",
            "Ё",
            "Ж",
            "З",
            "И",
            "Й",
            "К",
            "Л",
            "М",
            "Н",
            "О",
            "П",
            "Р",
            "С",
            "Т",
            "У",
            "Ф",
            "Х",
            "Ц",
            "Ч",
            "Ш",
            "Щ",
            "Ъ",
            "Ъ",
            "Ь",
            "Э",
            "Ю",
            "Я"
        ];

        return str_replace($convert_from, $convert_to, $string);
    }

    public static function cleanAllSpaces($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    public static function encrypt($stringToEncrypt, $validateKey = self::VALIDATE_KEY)
    {
        if (openssl_cipher_iv_length(self::ENCRYPTION_METHOD) === false) {
            throw StringsException::openssl_cipher_iv_length_Exception();
        }

        $cryptoStrong = true;
        $iv           = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::ENCRYPTION_METHOD), $cryptoStrong);

        if (false === $cryptoStrong || false === $iv) {
            throw new \RuntimeException('IV generation failed');
        }

        $stringEncrypted = openssl_encrypt($stringToEncrypt, self::ENCRYPTION_METHOD, $validateKey, 0, $iv);

        return base64_encode($stringEncrypted . self::ENCRYPTION_GLUE . $iv);
    }

    public static function decrypt($encryptedString, $validateKey = self::VALIDATE_KEY)
    {
        if (base64_decode($encryptedString) === false) {
            throw StringsException::base64_decode_Exception();
        }

        list($encryptedString, $iv) = explode(self::ENCRYPTION_GLUE, base64_decode($encryptedString), 2);

        return openssl_decrypt($encryptedString, self::ENCRYPTION_METHOD, $validateKey, 0, $iv);
    }

    public static function replaceFirstOccurrence($haystack, $needle, $replace)
    {
        $pos = strpos($haystack, $needle);

        if ($pos !== false && $pos === 0) {
            return substr_replace($haystack, $replace, $pos, strlen($needle));
        }

        return null;
    }
}

