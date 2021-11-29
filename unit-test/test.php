<?php
/*$data = "!@#^$%^&,*(a  ;s-da_s.";
$data = preg_replace('/[^a-zA-Z0-9._]|[,;]$/s', '', $data);

echo $data;*/
function replaceRegex($string) {
    //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    //$string = preg_replace('/[^A-Za-z0-9.\-()@,#\/&_\']/', ' ', $string); // Removes special chars.
    $string = preg_replace('/[^A-Za-z0-9.\-()@#:|+$;%<>\/,&_\']/', ' ', $string); // Removes special chars.
    $string = preg_replace('/ {2,}/',' ',$string);// Remove One++(1++) space from data.
    return $string;
}
echo replaceRegex('A!@#$H/-KUI)OI(OJ_L%^&')."</br>";

function decryptId($encrypted_id)
{
    $key = md5('My_key-12719', true);
    $data = pack('H*', $encrypted_id); // Translate back to binary
    $data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
    $data = base_convert($data, 36, 10);

    return $data;
}

echo decryptId('43d94e2b493da28b');
