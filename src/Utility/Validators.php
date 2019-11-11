<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validators
 *
 * @author matiasfuster
 */

namespace Solcre\SolcreFramework2\Utility;

class Validators
{
    public static function validPng($filename): bool
    {
        return strtolower(File::extension($filename)) === 'png';
    }

    public static function validIco($filename): bool
    {
        return strtolower(File::extension($filename)) === 'ico';
    }

    public static function validMp3($filename): bool
    {
        return strtolower(File::extension($filename)) === 'mp3';
    }

    public static function validFlash($filename)
    {
        return self::validSwf($filename) || self::validFlv($filename);
    }

    public static function validSwf($filename): bool
    {
        return strtolower(File::extension($filename)) === 'swf';
    }

    public static function validFlv($filename): bool
    {
        return strtolower(File::extension($filename)) === 'flv';
    }

    public static function validWebVideo($filename): bool
    {
        return self::validHtml5Video($filename) || self::validFlashVideo($filename);
    }

    public static function validHtml5Video($filename): bool
    {
        return in_array(strtolower(File::extension($filename)), ['webm', 'mp4', 'ogv', 'avi']);
    }

    public static function validFlashVideo($filename): bool
    {
        return self::validFlv($filename);
    }

    public static function validBanner($filename): bool
    {
        return self::validImage($filename) || self::validSwf($filename);
    }

    public static function validImage($filename): bool
    {
        return in_array(strtolower(File::extension($filename)), ['bmp', 'jpg', 'jpeg', 'gif', 'png', 'ico', 'svg', 'tif']);
    }

    public static function validTpl($filename): bool
    {
        return strtolower(File::extension($filename)) === 'tpl';
    }

    public static function validLang($filename): bool
    {
        return self::validTxt($filename) || self::validJs($filename);
    }

    public static function validTxt($filename): bool
    {
        return strtolower(File::extension($filename)) === 'txt';
    }

    public static function validJs($filename): bool
    {
        return strtolower(File::extension($filename)) === 'js';
    }

    public static function validDocument($filename): bool
    {
        return self::validPdf($filename) || self::validMsWord($filename);
    }

    public static function validPdf($filename): bool
    {
        return strtolower(File::extension($filename)) === 'pdf';
    }

    public static function validMsWord($filename): bool
    {
        return in_array(strtolower(File::extension($filename)), ['doc', 'docx']);
    }

    public static function validFile($filename): bool
    {
        $extension = File::extension($filename);
        return (empty($extension)) ? false : ! self::valid_script($filename);
    }

    public static function validScript($filename): bool
    {
        return in_array(
            strtolower(File::extension($filename)),
            ['dhtml', 'phtml', 'php3', 'php', 'php4', 'php5', 'jsp', 'jar', 'cgi', 'htaccess']
        );
    }

    public static function validZip($filename): bool
    {
        return strtolower(File::extension($filename)) === 'zip';
    }

    public static function validRar($filename): bool
    {
        return strtolower(File::extension($filename)) === 'rar';
    }

    public static function validArchive($filename): bool
    {
        return in_array(strtolower(File::extension($filename)), ['zip', 'rar']);
    }

    public static function validXls($filename): bool
    {
        return strtolower(File::extension($filename)) === 'xls';
    }

    public static function validUsername($username)
    {
        return preg_match('#^[a-z][\da-z_]{6,22}[a-z\d]\$#i', $username);
    }

    public static function validColor($color)
    {
        return preg_match('/^#(?:(?:[a-f\d]{3}){1,2})$/i', $color);
    }

    public static function validEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validDate2($date, $format): bool
    {
        $date = date_parse_from_format($format, $date);
        return checkdate($date['month'], $date['day'], $date['year']);
    }

    public static function validDateFuture($date): bool
    {
        return self::dateCompare($date, date('Y-m-d')) > 0;
    }

    private static function dateCompare($date1, $date2): int
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        if ($date1 - $date2 > 0) {
            return 1;
        }

        if ($date2 - $date1 > 0) {
            return -1;
        }

        return 0;
    }

    public static function validDatePast($date): bool
    {
        return self::dateCompare($date, date('Y-m-d')) < 0;
    }

    public static function validDateAfter($date1, $after_date): bool
    {
        return self::dateCompare($date1, $after_date) < 0;
    }

    public static function validDateBefore($date1, $before_date): bool
    {
        return self::dateCompare($date1, $before_date) > 0;
    }

    public static function validArray($array): bool
    {
        return is_array($array) && count($array) > 0;
    }

    public static function validDomain($dominio)
    {
        $arrayDom = explode('http://', $dominio);
        if ($arrayDom[0] === '') {
            $indice = 1;
        } else {
            $indice = 0;
        }
        if (substr($arrayDom[$indice], 0, 4) === 'www.') {
            $dominio = substr($arrayDom[$indice], 4, (strlen($arrayDom[$indice]) + 1));
        } else {
            $dominio = $arrayDom[$indice];
        }
        return $dominio;
    }

    public static function validCC($cardNumber): bool
    {
        $cardNumber = preg_replace('/\D|\s/', '', $cardNumber);  # strip any non-digits
        $cardlength = strlen($cardNumber);
        $parity = $cardlength % 2;
        $sum = 0;
        for ($i = 0; $i < $cardlength; $i++) {
            $digit = $cardNumber[$i];
            if ($i % 2 === $parity) {
                $digit *= 2;
            }
            if ($digit > 9) {
                $digit -= 9;
            }
            $sum += $digit;
        }

        return ($sum % 10 === 0);
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
