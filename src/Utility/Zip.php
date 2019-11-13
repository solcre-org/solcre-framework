<?php

namespace Solcre\SolcreFramework2\Utility;

use Solcre\SolcreFramework2\Exception\ZipException;
use ZipArchive;

class Zip
{
    public static function compressAndSend($dir, array $files, $zipname): void
    {
        $file = tempnam("/tmp", "zip");

        if ($file !== false) {
            $zip = new ZipArchive();
            $zip->open($file, \ZipArchive::OVERWRITE);

            if (is_array($files) && count($files)) {
                foreach ($files as $name) {
                    $zip->addFile($dir . $name, $name);
                }
            }

            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Length: ' . filesize($file));
            header('Content-Disposition: attachment; filename="' . $zipname . '.zip"');
            readfile($file);
            unlink($file);
            exit;
        }

        throw ZipException::temporaryFilenameException();
    }

    public static function extract($filename, $location = null, $delete = false): bool
    {
        $success  = false;
        $location = $location ?: dirname($filename);
        $zip      = new \ZipArchive();

        if ($zip->open($filename) === true) {
            $success = $zip->extractTo($location);
            $zip->close();

            if ($success && $delete) {
                unlink($filename);
            }
        }

        return $success;
    }

    public static function extractPattern($filename, $regex, $location = null, $delete = false)
    {
        $success = false;
        $extracted = [];
        $location = $location ? $location : dirname($filename) . '/';
        $zip = new \ZipArchive();

        if ($zip->open($filename) === true) {
            $success = true;
            $numFiles = $zip->numFiles;

            for ($i = 0; $i < $numFiles && $success; $i++) {
                $file = $zip->getNameIndex($i);

                if ($file === false) {
                    throw ZipException::nameOfIndexException();
                }

                $basename = basename($file);

                if (preg_match($regex, $basename)) {
                    $basename = Uploads::validName(Strings::cleanName($basename), $location);
                    $copy = copy("zip://" . $filename . "#" . $file, $location . $basename);

                    if ($copy) {
                        $extracted[] = $basename;
                    } else {
                        $success = false;
                    }
                }
            }

            $zip->close();

            if (! $success) {
                $countExtracted = count($extracted);

                for ($i = 0; $i < $countExtracted; $i++) {
                    unlink($location . $extracted[$i]);
                }
            }

            if ($success && $delete) {
                unlink($filename);
            }
        }

        return $success ? $extracted : false;
    }

    protected static function uniqueName($name, $folder): string
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

            $file     = $name;
            $filepath = $folder . $name . $ext;
            $i        = 0;

            while (is_file($filepath)) {
                $i++;
                $filepath = $folder . $name . $i . $ext;
                $file     = $name . $i;
            }

            return $file . $ext;
        }

        throw ZipException::invalidNameException();
    }
}
