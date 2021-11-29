<?php

//echo md5("1234567");

function encryptId($id)
{
    $key = md5('My_key-12719', true);
    $id = base_convert($id, 10, 36); // Save some space
    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $id, 'ecb');
    $data = bin2hex($data);

    return $data;
}

function decryptId($encrypted_id)
{
    $key = md5('My_key-12719', true);
    $data = pack('H*', $encrypted_id); // Translate back to binary
    $data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
    $data = base_convert($data, 36, 10);

    return $data;
}
//$o = '2341234789303456';
//$enc = encryptId($o);
//echo $enc;
//echo '<br />';
//$d = decryptId($enc);
//echo $d;
//echo '<br />';
//echo $o == $d;
echo normalizeString("R48100G1_Rectifier_Data_Sheet(1U,96%)_06-(20140404)_19012017123227.zip");

function normalizeString ($str = '')
{
    $str = strip_tags($str);
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace(' ', '-', $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    $str = str_replace('--', '-', $str);
    return $str;
}
?>