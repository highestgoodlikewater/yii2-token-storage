<?php
namespace canis\tokenStorage\encryption\adapters;

use phpseclib\Crypt\RSA as Crypt_RSA;
use yii\base\InvalidConfigException;
use canis\tokenStorage\encryption\AdapterInterface;

    /*
    http://phpseclib.sourceforge.net/rsa/examples.html
     */


abstract class BaseEncryptionKey
    extends BaseAdapter
{
    protected $publicKey = false;
    protected $privateKey = false;

    abstract protected function loadKeys();

    protected function generateKeys()
    {
        $rsa = new Crypt_RSA();
        $rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);
        defined('CRYPT_RSA_EXPONENT') || define('CRYPT_RSA_EXPONENT', 65537);
        defined('CRYPT_RSA_SMALLEST_PRIME') || define('CRYPT_RSA_SMALLEST_PRIME', 64);
        return $rsa->createKey($this->config['keySize']);
    }

    protected function getPublicKey()
    {
        return $this->publicKey;
    }

    protected function getPrivateKey()
    {
        return $this->privateKey;
    }

    private function getKeyPair()
    {
        if (!$this->loadKeys()) {
            return false;
        }
        return [
            'private' => $this->getPrivateKey(),
            'public' => $this->getPublicKey()
        ];
    }

    public function encrypt($token)
    {

    }

    public function decrypt($encryptedToken)
    {

    }

    public function isAvailable()
    {
        return $this->loadKeys() !== false;
    }

    static public function defaultConfig()
    {
        return array_merge(static::defaultConfig(), [
            'keySize' => 1024
        ]);
    }

    static public function requiredConfig()
    {
        return array_merge(static::requiredConfig(), ['keySize']);
    }
}
