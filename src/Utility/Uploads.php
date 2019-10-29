<?php

namespace Solcre\SolcreFramework2\Utility;

class Uploads
{

    public static function validName($name, $folder)
    {
        $name = self::withoutCommas($name);
        $name = self::nameWithoutSpaces($name);
        $name = self::nameUnique($name, $folder);
        return $name;
    }


    public static function nameUnique($name, $folder)
    {
        if ($name) {
            $t = explode('.', $name);
            if (count($t) == 1) {
                $ext = '';
            } else {
                $ext = "." . $t[count($t) - 1];
                $t = array_slice($t, 0, count($t) - 1);
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
        }
        return $file . $ext;
    }

    public static function nameWithoutSpaces($name)
    {
        return str_replace(" ", "_", $name);
    }

    public static function withoutCommas($name)
    {
        return str_replace("'", "", stripslashes($name));
    }

    public static function safeFileName($fileName, $rutaAbs)
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

?>