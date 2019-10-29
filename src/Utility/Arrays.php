<?php
/**
 * Description of Arrays
 *
 * @author matiasfuster
 */

namespace Solcre\SolcreFramework2\Utility;

class Arrays
{

    public static function utf8_decode(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::utf8_decode($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = utf8_decode($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function utf8_encode(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::utf8_encode($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = utf8_encode($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function htmlentitiesUTF8(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::htmlentities($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = htmlentities($elem, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                    }
                }
            }
        }
        return $array;
    }

    public static function htmlentities(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::htmlentities($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = htmlentities($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function html_entity_decode(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::html_entity_decode($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = html_entity_decode($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function stripslashes(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::stripslashes($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = stripslashes($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function stripTags(Array $array = null)
    {
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

    public static function addslashes(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::addslashes($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = addslashes($elem);
                    }
                }
            }
        }
        return $array;
    }

    /**
     * Converts an array to uppercase.
     * @param $array Array Array to convert to uppercase.
     * @author Matias Fuster
     */
    public static function fullUpper(Array $array = null)
    {
        return self::fullUpper_array($array);
    }

    /**
     * Converts an array to uppercase.
     * @deprecated Use fullUpper instead
     * @param $array Array Array to convert to uppercase.
     * @author Matias Fuster
     */
    public static function fullUpper_array(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::fullUpper_array($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = Strings::fullUpper($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function clean_for_json(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::clean_for_json($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = Strings::clean_for_json($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function func_over($func, Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::func_overy($func, $elem);
                } else {
                    $elem = $func($elem);
                }
            }
        }
        return $array;
    }

    public static function html_entity_decode_array(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::html_entity_decode_array($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = html_entity_decode($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function guion(Array $array = null)
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::guion($elem);
                } else {
                    if (is_string($elem)) {
                        $elem = Strings::guion($elem);
                    }
                }
            }
        }
        return $array;
    }

    public static function default_text(Array $array = null, $value = '-')
    {
        if (count($array) > 0) {
            foreach ($array as &$elem) {
                if (is_array($elem)) {
                    $elem = self::default_text($elem, $value);
                } else {
                    $elem = Strings::default_text($elem, $value);
                }
            }
        }
        return $array;
    }

    private static function comparePropierty($array1, $array2, $propertyName)
    {
        return strnatcmp($array1[$propertyName], $array2[$propertyName]);
    }

    public static function sortArray($array, $propertyName)
    {
// sort alphabetically by name
        usort($array, function ($a, $b) use ($propertyName) {
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

    public static function array_map_recursive($func, array $arr, $userData = null)
    {
        array_walk_recursive($arr, function (&$v) use ($func, $userData) {
            if (is_array($func)) {
                $v = $func[0]->$func[1]($userData);
            } else {
                $v = $func($v, $userData);
            }
        });
        return $arr;
    }

    public static function only_digits(Array $array = null)
    {
        if (is_array($array)) {
            return array_filter($array, 'ctype_digit');
        }
    }

    public static function array_depth($arr)
    {
        if (!is_array($arr)) {
            return 0;
        }
        $arr = json_encode($arr);
        $varsum = 0;
        $depth = 0;
        for ($i = 0; $i < strlen($arr); $i++) {
            $varsum += intval($arr[$i] == '[') - intval($arr[$i] == ']');
            if ($varsum > $depth) {
                $depth = $varsum;
            }
        }
        return $depth;
    }

    public static function getValue($key, Array $array, $filters = null)
    {
        $value = null;
        if (is_array($array) && (count($array) > 0) && array_key_exists($key, $array)) {
            $value = $array[$key];
        }
        if (!empty($filters) && is_string($filters) && !empty($value)) {
            if (strpos($filters, '|') !== false) {
                $filters = split('|', $filters);
            }
            if (Validators::valid_array($filters)) {
                foreach ($filters as $filter) {
                    $value = self::applyFilter($filter, $value);
                }
            } else {
                if (is_string($filters)) {
                    $value = self::applyFilter($filters, $value);
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

    public static function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? self::object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public static function trim_array($Input)
    {
        if (!is_array($Input)) {
            return trim($Input);
        }
        return array_map('self::trim_array', $Input);
    }
}

?>
