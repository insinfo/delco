<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 10/01/2018
 * Time: 12:02
 */

namespace Delco\Utils;

use Delco\Utils\Utils;

class Log
{
    public static function e($message = '', $tag = '')
    {
        self::writeLog($message, $tag);
    }

    private static function writeLog($message = '', $tag = '')
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data = date('d/m/Y H:i:s.u', time());
        $log = 'message: ' . $message . ' tag: ' . $tag . ' date: ' . $data . ' ';

        $fn = '/var/www/html/siscec/log/log.log';
        file_put_contents($fn, $log . "\r\n", FILE_APPEND | LOCK_EX);

        if (filesize($fn) > 50000000)//50000000 Byte = 50 Megabyte
        {
            $fp = fopen($fn, "r+");
            if (flock($fp, LOCK_EX))
            {
                fseek($fp, 0);
                fwrite($fp, '');
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    private static function countFileLines($file)
    {
        $linecount = 0;
        $handle = fopen($file, "r");
        while (!feof($handle))
        {
            $line = fgets($handle);
            $linecount++;
        }
        fclose($handle);
        return $linecount;
    }
}