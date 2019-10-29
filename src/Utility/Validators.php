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

    public static function valid_png($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('png'));
    }

    public static function valid_ico($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('ico'));
    }

    public static function valid_mp3($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('mp3'));
    }

    public static function valid_flash($filename)
    {
        return self::valid_swf($filename) || self::valid_flv($filename);
    }

    public static function valid_swf($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('swf'));
    }

    public static function valid_flv($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('flv'));
    }

    public static function valid_web_video($filename)
    {
        return self::valid_html5_video($filename) || self::valid_flash_video($filename);
    }

    public static function valid_html5_video($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('webm', 'mp4', 'ogv', 'avi'));
    }

    public static function valid_flash_video($filename)
    {
        return self::valid_flv($filename);
    }

    public static function valid_banner($filename)
    {
        return self::valid_image($filename) || self::valid_swf($filename);
    }

    public static function valid_image($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('bmp', 'jpg', 'jpeg', 'gif', 'png', 'ico', 'svg', 'tif'));
    }

    public static function valid_tpl($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('tpl'));
    }

    public static function valid_lang($filename)
    {
        return self::valid_txt($filename) || self::valid_js($filename);
    }

    public static function valid_txt($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('txt'));
    }

    public static function valid_js($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('js'));
    }

    public static function valid_document($filename)
    {
        return self::valid_pdf($filename) || self::valid_msword($filename);
    }

    public static function valid_pdf($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('pdf'));
    }

    public static function valid_msword($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('doc', 'docx'));
    }

    public static function valid_file($filename)
    {
        $extension = File::extension($filename);
        return (empty($extension)) ? false : !self::valid_script($filename);
    }

    public static function valid_script($filename)
    {
        return in_array(
            strtolower(File::extension($filename)),
            array("dhtml", "phtml", "php3", "php", "php4", "php5", "jsp", "jar", "cgi", "htaccess")
        );
    }

    public static function valid_zip($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('zip'));
    }

    public static function valid_rar($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('rar'));
    }

    public static function valid_archive($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('zip', 'rar'));
    }

    public static function valid_xls($filename)
    {
        return in_array(strtolower(File::extension($filename)), array('xls'));
    }

    public static function valid_username($username)
    {
        return preg_match("#^[a-z][\da-z_]{6,22}[a-z\d]\$#i", $username);
    }

    public static function valid_color($color)
    {
        return preg_match('/^#(?:(?:[a-f\d]{3}){1,2})$/i', $color);
    }

    public static function valid_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function valid_date2($date, $format)
    {
        $date = date_parse_from_format($format, $date);
        return checkdate($date['month'], $date['day'], $date['year']);
    }

    public static function valid_date_future($date)
    {
        return self::date_compare($date, date('Y-m-d')) > 0;
    }

    private static function date_compare($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        if ($date1 - $date2 > 0) {
            return 1;
        } else {
            if ($date2 - $date1 > 0) {
                return -1;
            }
        }
        return 0;
    }

    public static function valid_date_past($date)
    {
        return self::date_compare($date, date('Y-m-d')) < 0;
    }

    public static function valid_date_after($date1, $after_date)
    {
        return self::date_compare($date1, $after_date) < 0;
    }

    public static function valid_date_before($date1, $before_date)
    {
        return self::date_compare($date1, $before_date) > 0;
    }

    public static function valid_array($array)
    {
        return is_array($array) && count($array) > 0;
    }

    public static function valid_domain($dominio)
    {
        $arrayDom = explode('http://', $dominio);
        if ($arrayDom[0] == "") {
            $indice = 1;
        } else {
            $indice = 0;
        }
        if (substr($arrayDom[$indice], 0, 4) == 'www.') {
            $dominio = substr($arrayDom[$indice], 4, (strlen($arrayDom[$indice]) + 1));
        } else {
            $dominio = $arrayDom[$indice];
        }
        return $dominio;
    }

    public static function valid_date($date)
    {
        # dd-mm-yyyy
        $seperator = "[\/\-\.]";
        return preg_match(
            "#^(((0?[1-9]|1\d|2[0-8]){$seperator}(0?[1-9]|1[012])|(29|30){$seperator}(0?[13456789]|1[012])|31{$seperator}(0?[13578]|1[02])){$seperator}(19|[2-9]\d)\d{2}|29{$seperator}0?2{$seperator}((19|[2-9]\d)(0[48]|[2468][048]|[13579][26])|(([2468][048]|[3579][26])00)))$#",
            $date
        ) == 1 ? true : false;
    }

    public static function valid_ip($ip_address)
    {
        $val_0_to_255 = "(25[012345]|2[01234]\d|[01]?\d\d?)";
        $pattern = "#^($val_0_to_255\.$val_0_to_255\.$val_0_to_255\.$val_0_to_255)$#";
        return preg_match($pattern, $ip_address, $matches);
    }

    public static function valid_cc($cardnumber)
    {
        $cardnumber = preg_replace("/\D|\s/", "", $cardnumber);  # strip any non-digits
        $cardlength = strlen($cardnumber);
        $parity = $cardlength % 2;
        $sum = 0;
        for ($i = 0; $i < $cardlength; $i++) {
            $digit = $cardnumber[$i];
            if ($i % 2 == $parity) {
                $digit = $digit * 2;
            }
            if ($digit > 9) {
                $digit = $digit - 9;
            }
            $sum = $sum + $digit;
        }
        $valid = ($sum % 10 == 0);
        return $valid;
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}

?>
