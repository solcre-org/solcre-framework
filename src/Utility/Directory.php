<?php

namespace Solcre\SolcreFramework2\Utility;

class Directory
{
    public static function addDirectory($path, $permissions = 0777)
    {
        return !is_dir($path) && mkdir($path, $permissions);
    }

    public static function include_dir($path, $read = false)
    {
        //separador de directorios
        $s = '/';
        //vemos si es la primera vez que usamos la funcion
        if (!$read) {
            //obtenemos los dos ultimos caracteres
            $tree = substr($path, -2);
            if ($tree == '.*') {
                //eliminamos el asterisco y activamos la recursividad
                $path = preg_replace('!\.\*$!', '', $path);
                $read = true;
            }
            //obtenemos el document_root del archivo en caso de usarse
            $path = preg_replace('!^root\.!', $_SERVER['DOCUMENT_ROOT'] . $s, $path);
            //cambiamos el punto por el separador
            /* HOTFIX */
            $path = str_replace('..', ',,', $path);
            $path = str_replace('.', $s, $path);
            $path = str_replace(',,', '..', $path);
        }
        //abrimos el directorio
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    //si es un directorio lo recorremos en caso de activar la recursividad
                    if (is_dir($path . $s . $file) and $read) {
                        include_dir($path . $s . $file, true);
                    } else {
                        $ext = substr(strtolower($file), -3);
                        if ($ext == 'php') {
                            include_once($path . $s . $file);
                        }
                    }
                }
            }
            //cerramos el directorio
            closedir($handle);
        }
    }

    public static function dir_contents($dir)
    {
        $files = scandir($dir);
        unset($files[array_search('.', $files)]);
        unset($files[array_search('..', $files)]);
        return array_values($files);
    }

    public static function is_subdir($dir, $subdir)
    {
        $dir = realpath($dir);
        $subdir = realpath($subdir);
        return is_dir($dir) && is_dir($subdir) && (strpos($subdir, $dir) === 0);
    }

    public static function dir_subdirs($dir, $pattern = null)
    {
        $match = !empty($pattern);
        $fulldirs = glob(Strings::remove_last_slashes($dir) . '/*', GLOB_ONLYDIR);
        array_walk($fulldirs, function ($val, $key) use (&$fulldirs, $pattern, $match) {
            if (!$match || ($match && preg_match($pattern, $val))) {
                $fulldirs[$key] = basename($val);
            } else {
                unset($fulldirs[$key]);
            }
        });
        return array_values($fulldirs);
    }

    public static function dir_subfiles($dir, $pattern = null)
    {
        $match = !empty($pattern);
        $files = array_diff(self::dir_contents($dir), self::dir_subdirs($dir));
        array_walk($files, function ($val, $key) use (&$files, $pattern, $match) {
            if ($match && !preg_match($pattern, $val)) {
                unset($files[$key]);
            }
        });
        return array_values($files);
    }

    public static function dir_delete($dirname)
    {
        $retorno = false;
        if (is_dir($dirname)) {
            $retorno = true;
            $contents = self::dir_contents($dirname);
            $count = count($contents);
            for ($i = 0; $i < $count; $i++) {
                $file = $contents[$i];
                if (is_dir($dirname . "/" . $file)) {
                    $retorno = self::dir_delete($dirname . '/' . $file);
                } else {
                    unlink($dirname . "/" . $file);
                }
            }
            $retorno = rmdir($dirname);
        }
        return $retorno;
    }

    public static function getFolderSize($path)
    {
        if (!file_exists($path)) {
            return 0;
        }
        if (is_file($path)) {
            return filesize($path);
        }
        $ret = 0;
        foreach (glob($path . "/*") as $fn) {
            $ret += self::getFolderSize($fn);
        }
        return $ret;
    }

    public static function getFolderName($name, $path)
    {
        $name = self::nameWithoutCommas($name);
        $name = self::nameWithoutSpaces($name);
        $name = self::uniqueNameFolder($name, $path);
        return $name;
    }

    public static function getFolderDate($path)
    {
        return date('Y-m-d H:i:s', filemtime($path));
    }

    public static function nameWithoutSpaces($name)
    {
        return str_replace(" ", "_", $name);
    }

    public static function nameWithoutCommas($name)
    {
        return str_replace("'", "", stripslashes($name));
    }

    public static function uniqueNameFolder($name, $folder)
    {
        if (!empty($name)) {
            $resource = $folder . $name;
            $i = 0;
            while (is_dir($resource)) {
                $i++;
                $resource = $folder . $name . $i;
                $name = $name . $i;
            }
        }
        return $name;
    }

    public static function renameDirectory($oldPath, $newPath)
    {
        if (!is_dir($newPath)) {
            return rename($oldPath, $newPath);
        } else {
            return false;
        }
    }

}

?>
