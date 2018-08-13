<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 08/03/2018
 * Time: 11:42
 */

namespace Delco\Util;

//chaves de userdata encriptados pelo site
//https://www.tools4noobs.com/online_tools/encrypt/
//usando Algorithm: blowfish Mode: CBC Key: jubarte  Encode: Hexa

class Criptografia
{
    public static $key = '9B7D2C34A366BF890C730641E6CECF6F';
    public static $nonce = '/gUdnEgLZYoXANCCuxPo+DoBxO9Q+bDC';

    /**
     * O metodo responsavel por descriptograr uma mensagem
     * @param string $ciphertext Mensagem criptografada
     * @param string $key Chave para realizar a descriptografia precisa ser a mesma usada na criptografia Exemplo: "skjj400ndkdçg00"
     * @return mixed
     */
    public static function decrypt($ciphertext, $key)
    {
        $nonce = file_get_contents('/var/www/html/jubarte/BackEnd/encryption64.key');

        $plain = \Sodium\crypto_secretbox_open(
            base64_decode($ciphertext),
            base64_decode($nonce),
            $key
        );

        //\Sodium\memzero($ciphertext);
        //\Sodium\memzero($key);
        return $plain;
    }

    /**
     * O metodo responsavel por criptofrafar uma mensagem
     * @param string $message Mensagem a ser criptograda
     * @param string $key Chave para realizar a criptografia Exemplo: "9B7D2C34A366BF890C730641E6CECF6F"
     * @return  string
     */
    public static function encrypt($message, $key)
    {
        //$nonce = \Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_NONCEBYTES);
        //file_put_contents('/var/www/html/jubarte/BackEnd/encryption64.key', base64_encode($nonce));
        $nonce = file_get_contents('/var/www/html/jubarte/BackEnd/encryption64.key');

        $cipher = base64_encode(
            \Sodium\crypto_secretbox(
                $message,
                base64_decode($nonce),
                $key
            )
        );
       // \Sodium\memzero($message);
       // \Sodium\memzero($key);
        return $cipher;

    }
}