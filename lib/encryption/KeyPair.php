<?php
namespace canis\tokenStorage\encryption;

use phpseclib\Crypt\RSA as Crypt_RSA;

class KeyPair
	extends BaseKeyPair
{
    private $publicKey;
    private $privateKey;

    public function __construct($keySize = 2048)
    {
    	$keys = static::doGenerateKeys($keySize);
    	if (!$keys) {
    		// @todo find right exception
    		throw new \Exception("Unable to generate keys for key pair");
    	}
        $this->publicKey = $keys['publickey'];
        $this->privateKey = $keys['privatekey'];
    }

    public function encrypt($token)
    {
        defined('CRYPT_RSA_PKCS15_COMPAT') || define('CRYPT_RSA_PKCS15_COMPAT', true);
        $rsa = new Crypt_RSA();
        $rsa->loadKey($this->publicKey);
        $rsa->setEncryptionMode(Crypt_RSA::ENCRYPTION_PKCS1);
        return $rsa->encrypt($token);
    }

    public function decrypt($encryptedToken)
    {
        defined('CRYPT_RSA_PKCS15_COMPAT') || define('CRYPT_RSA_PKCS15_COMPAT', true);
        $rsa = new Crypt_RSA();
        $rsa->loadKey($this->privateKey);
        $rsa->setEncryptionMode(Crypt_RSA::ENCRYPTION_PKCS1);
        return $rsa->decrypt($encryptedToken);
    }
}
