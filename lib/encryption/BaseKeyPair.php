<?php
namespace canis\tokenStorage\encryption;

use phpseclib\Crypt\RSA as Crypt_RSA;

abstract class BaseKeyPair
	implements KeyPairInterface
{
    protected static function doGenerateKeys($keySize = 2048)
    {
        $rsa = new Crypt_RSA();
        $rsa->setPrivateKeyFormat(Crypt_RSA::PRIVATE_FORMAT_PKCS1);
        $rsa->setPublicKeyFormat(Crypt_RSA::PUBLIC_FORMAT_PKCS1);
        defined('CRYPT_RSA_EXPONENT') || define('CRYPT_RSA_EXPONENT', 65537);
        defined('CRYPT_RSA_SMALLEST_PRIME') || define('CRYPT_RSA_SMALLEST_PRIME', 64);
        return $rsa->createKey($keySize);
    }
}
