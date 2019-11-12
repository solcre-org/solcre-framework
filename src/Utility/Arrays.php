<?php
/**
 * Description of Arrays
 *
 * @author matiasfuster
 */

namespace Solcre\SolcreFramework2\Utility;

use Solcre\SolcreFramework2\Exception\ArraysException;

class Arrays
{
    public function checkValidParam($array): void
    {
        if ( null === $array) {
            throw ArraysException::nonCountableException();
        }
    }

    public static function utf8Decode(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::utf8Decode($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = utf8_decode($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function utf8Encode(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::utf8Encode($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = utf8_encode($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function htmlentitiesUTF8(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::htmlentitiesUTF8($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = htmlentities($elem, ENT_QUOTES | ENT_IGNORE, 'UTF-8');
                    }
                }
            }
        }

        return $array;
    }

    public static function htmlEntities(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {

                    $elem = self::htmlEntities($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = htmlentities($elem);
                    }
                }
            }
        }


        return $array;
    }

    public static function htmlEntityDecode(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::htmlEntityDecode($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = html_entity_decode($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function stripSlashes(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::stripSlashes($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = stripslashes($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function stripTags(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::stripTags($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = strip_tags($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function addSlashes(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::addSlashes($elem);
                } else {

                    if (is_string($elem)) {
                        $elem = addslashes($elem);
                    }
                }
            }
        }

        return $array;
    }

    public static function funcOver($func, array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::funcOver($func, $elem);
                } else {
                    $elem = $func($elem);
                }
            }
        }

        return $array;
    }

    public static function htmlEntityDecodeArray(array $array = null): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {
                if (is_array($elem)) {

                    $elem = self::htmlEntityDecodeArray($elem);
                } elseif (is_string($elem)) {
                    $elem = html_entity_decode($elem);
                }
            }
        }

        return $array;
    }

    public static function defaultText(array $array = null, $value = '-'): array
    {
        $this->checkValidParam($array);

        if (count($array) > 0) {

            foreach ($array as &$elem) {

                if (is_array($elem)) {
                    $elem = self::defaultText($elem, $value);
                } else {
                    $elem = Strings::defaultText($elem, $value);
                }
            }
        }

        return $array;
    }

    public static function sortArray($array, $propertyName)
    {
        // sort alphabetically by name
        usort($array, static function ($a, $b) use ($propertyName) {
            $aProperty = '';
            $bProperty = '';

            if (is_object($a)) {
                $funcion = "get" . ucfirst($propertyName);

                if (method_exists($a, $funcion)) {
                    $aProperty = $a->$funcion();
                }
            } else {
                $aProperty = $a[$propertyName];
            }

            if (is_object($b)) {
                $funcion = "get" . ucfirst($propertyName);

                if (method_exists($b, $funcion)) {
                    $bProperty = $b->$funcion();
                }
            } else {
                $bProperty = $b[$propertyName];
            }

            return strCmp($aProperty, $bProperty);
        });

        return $array;
    }

    public static function arrayMapRecursive($func, array $arr, $userData = null): array
    {
        array_walk_recursive($arr, static function (&$v) use ($func, $userData) {
            if (is_array($func)) {
                $v = $func[0]->$func[1]($userData);
            } else {
                $v = $func($v, $userData);
            }
        });

        return $arr;
    }

    public static function onlyDigits(array $array = null): ?array
    {
        if (is_array($array)) {
            return array_filter($array, 'ctype_digit');
        }
    }

    public static function arrayDepth($arr): int
    {
        if (! is_array($arr)) {
            return 0;
        }

        $arr    = \json_encode($arr, JSON_THROW_ON_ERROR, 512);
        $sum    = 0;
        $depth  = 0;
        $length = strlen($arr);

        for ($i = 0; $i < $length; $i++) {
            $sum += (int)($arr[$i] === '[') - (int)($arr[$i] === ']');

            if ($sum > $depth) {
                $depth = $sum;
            }
        }

        return $depth;
    }

    public static function getValue($key, array $array, $filters = null)
    {
        $value = null;

        if (is_array($array) && \array_key_exists($key, $array) && (count($array) > 0)) {
            $value = $array[$key];
        }

        if (! empty($filters) && is_string($filters) && ! empty($value)) {

            if (strpos($filters, '|') !== false) {
                $filters = \explode('|', $filters);
            }

            if (Validators::validArray($filters)) {

                foreach ($filters as $filter) {
                    $value = self::applyFilter($filter, $value);
                }
            }
        }

        return $value;
    }

    private static function applyFilter($filterName, $value)
    {
        $filterName = trim($filterName);

        switch ($filterName) {
            case 'trim':
                $value = trim($value);
                break;
            case 'int':
                $value = (int)$value;
                break;
        }

        return $value;
    }

    public static function objectToArray($obj): array
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        $arr  = [];

        foreach ($_arr as $key => $val) {
            $val       = (is_array($val) || is_object($val)) ? self::objectToArray($val) : $val;
            $arr[$key] = $val;
        }

        return $arr;
    }

    public static function trimArray($Input)
    {
        if (! is_array($Input)) {
            return trim($Input);
        }

        return array_map('self::trim_array', $Input);
    }
}
