<?php
function getObfuscationSalt()
{
    $salt_file = __DIR__ . '/../config/obfuscation_salt.php';
    if (file_exists($salt_file)) {
        require $salt_file;
    } else {
        $bytes = openssl_random_pseudo_bytes(4);
        $sf = fopen($salt_file, 'wb');
        fwrite($sf, chr(60) . "?php\n");
        fwrite($sf, 'const OBFUSCATION_SALT = 0x' . bin2hex($bytes) . ";\n");
        fclose($sf);
        require $salt_file;
    }
    return OBFUSCATE_SALT;
}

/*
This is a simple reversible hash function I made for encoding and decoding test IDs.
It is not cryptographically secure, don't use it to hash passwords or something!
*/
function obfdeobf($id, $dec)
{
    $salt = OBFUSCATE_SALT & 0xFFFFFFFF;
    $id &= 0xFFFFFFFF;
    if ($dec) {
        $id ^= $salt;
        $id = (($id & 0xAAAAAAAA) >> 1) | ($id & 0x55555555) << 1;
        $id = (($id & 0x0000FFFF) << 16) | (($id & 0xFFFF0000) >> 16);
        return $id;
    }

    $id = (($id & 0x0000FFFF) << 16) | (($id & 0xFFFF0000) >> 16);
    $id = (($id & 0xAAAAAAAA) >> 1) | ($id & 0x55555555) << 1;
    return $id ^ $salt;
}

function obfuscateId($id)
{
    return str_pad(base_convert(obfdeobf($id + 1, false), 10, 36), 7, 0, STR_PAD_LEFT);
}

function deobfuscateId($id)
{
    return obfdeobf(base_convert($id, 36, 10), true) - 1;
}

//IMPORTANT: DO NOT ADD ANYTHING BELOW THE PHP CLOSING TAG, NOT EVEN EMPTY LINES!
