<?php

namespace Solcre\SolcreFramework2\Utility;

use Solcre\SolcreFramework2\Exception\DirectoryException;
use function array_search;
use function preg_replace;
use function str_replace;
use function substr;

class Directory
{
    public static function addDirectory($path, $permissions = 0777): bool
    {
        return ! is_dir($path) && ! mkdir($path, $permissions) && ! is_dir($path);
    }

    public static function includeDir($path, $read = false): void
    {
        //separador de directorios
        $s = '/';

        //vemos si es la primera vez que usamos la funcion
        if (! $read) {
            //obtenemos los dos ultimos caracteres
            $tree = substr($path, -2);

            if ($tree === '.*') {
                //eliminamos el asterisco y activamos la recursividad
                $path = preg_replace('!\.\*$!', '', $path);
                $read = true;
            }

            //obtenemos el document_root del archivo en caso de usarse
            $path = preg_replace('!^root\.!', $_SERVER['DOCUMENT_ROOT'] . $s, $path);
            //cambiamos el punto por el separador
            /* HOTFIX */
            $path = str_replace(['..', '.', ',,'], [',,', $s, '..'], $path);
        }

        //abrimos el directorio
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file !== '.' && $file !== '..') {
                    //si es un directorio lo recorremos en caso de activar la recursividad
                    if (is_dir($path . $s . $file) && $read) {
                        self::includeDir($path . $s . $file, true);
                    } else {
                        $ext = strtolower(substr($file, -3));

                        if ($ext === 'php') {
                            include_once($path . $s . $file);
                        }
                    }
                }
            }

            //cerramos el directorio
            closedir($handle);
        }
    }

    public static function dirDelete($dirname): bool
    {
        $retorno = false;

        if (is_dir($dirname)) {
            $contents = self::dirContents($dirname);

            foreach ($contents as $iValue) {
                $file = $iValue;

                if (is_dir($dirname . '/' . $file)) {
                    self::dirDelete($dirname . '/' . $file);
                } else {
                    unlink($dirname . '/' . $file);
                }
            }

            $retorno = rmdir($dirname);
        }

        return $retorno;
    }

    public static function dirContents($dir): array
    {
        $files = scandir($dir);

        if (! is_array($files)) {
            throw DirectoryException::scandirException();
        }

        unset($files[array_search('.', $files, true)], $files[array_search('..', $files, true)]);

        return array_values($files);
    }

    public static function getFolderSize($path): int
    {
        if (! file_exists($path)) {
            return 0;
        }

        if (is_file($path)) {
            if (filesize($path) === false) {
                throw DirectoryException::filesizeException();
            }

            return filesize($path);
        }

        $ret = 0;
        $globPath = (glob($path . '/*'));

        if ($globPath === false) {
            throw DirectoryException::globPathException();
        }

        foreach ($globPath as $fn) {
            $ret += self::getFolderSize($fn);
        }

        return $ret;
    }

    public static function getFolderName($name, $path): string
    {
        $name = self::nameWithoutCommas($name);
        $name = self::nameWithoutSpaces($name);
        $name = self::uniqueNameFolder($name, $path);

        return $name;
    }

    public static function nameWithoutCommas($name)
    {
        return str_replace("'", '', stripslashes($name));
    }

    public static function nameWithoutSpaces($name)
    {
        return str_replace(' ', '_', $name);
    }

    public static function uniqueNameFolder($name, $folder): string
    {
        if (! empty($name)) {
            $resource = $folder . $name;
            $i = 0;

            while (is_dir($resource)) {
                $i++;
                $resource = $folder . $name . $i;
                $name .= $i;
            }
        }

        return $name;
    }

    public static function getFolderDate($path)
    {
        $fileTime = filemtime($path);

        if ($fileTime === false) {
            throw DirectoryException::fileTimeException();
        }

        return date('Y-m-d H:i:s', $fileTime);
    }

    public static function renameDirectory($oldPath, $newPath): ?bool
    {
        if (! is_dir($newPath)) {
            return rename($oldPath, $newPath);
        }

        return false;
    }
}
