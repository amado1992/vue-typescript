<?php

namespace App\Utils;

class Encriptar
{
    private $key;
    private $iv;
    private $encrypt_method;

    public function __construct()
    {
        $this->key = config('app.key');
        $this->encrypt_method = config('app.cipher');
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->encrypt_method));
    }

    public function encrypt($string)
    {
        if ($encrypted = openssl_encrypt($string, $this->encrypt_method, $this->key, OPENSSL_RAW_DATA, $this->iv)) {
            return base64_encode($this->iv . ':::' . $encrypted);
        } else {
            return false;
        }
    }

    public function decrypt($string)
    {
        $string = base64_decode($string);
        $parts = explode(":::", $string);
        $iv = $parts[0];
        $encrypted = $parts[1];
        if ($decrypted = openssl_decrypt($encrypted, $this->encrypt_method, $this->key, OPENSSL_RAW_DATA, $iv)) {
            return $decrypted;
        } else {
            return false;
        }
    }
}
