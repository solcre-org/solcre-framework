<?php

namespace Solcre\SolcreFramework2\Utility;

use Solcre\SolcreFramework2\Exception\UploadsException;
use function array_slice;
use function count;

class Uploads
{
    public static function validName($name, $folder): string
    {
        $name = self::withoutCommas($name);
        $name = self::nameWithoutSpaces($name);
        return self::nameUnique($name, $folder);
    }

    public static function withoutCommas($name)
    {
        return str_replace("'", '', stripslashes($name));
    }

    public static function nameWithoutSpaces($name)
    {
        return str_replace(' ', '_', $name);
    }

    public static function nameUnique($name, $folder): string
    {
        if ($name) {
            $t = explode('.', $name);

            if (count($t) === 1) {
                $ext = '';
            } else {
                $ext  = '.' . $t[count($t) - 1];
                $t    = array_slice($t, 0, count($t) - 1);
                $name = implode('.', $t);
            }
            $file = $name;
            $filepath = $folder . $name . $ext;
            $i = 0;

            while (is_file($filepath)) {
                $i++;
                $filepath = $folder . $name . $i . $ext;
                $file = $name . $i;
            }

            return $file . $ext;
        }

        throw UploadsException::invalidNameException();
    }

    public static function safeFileName($fileName, $rutaAbs): string
    {
        $name = pathinfo($rutaAbs . $fileName, PATHINFO_FILENAME);
        $extension = pathinfo($rutaAbs . $fileName, PATHINFO_EXTENSION);
        $increment = ''; //start with no suffix

        while (file_exists($rutaAbs . $name . $increment . '.' . $extension)) {
            $increment++;
        }

        return $name . $increment . '.' . $extension;
    }
}
