<?php
namespace canis\tokenStorage\encryption\adapters;

use yii\base\InvalidConfigException;
use canis\tokenStorage\encryption\AdapterInterface;
use canis\tokenStorage\encryption\KeyPair;

    /*
    http://phpseclib.sourceforge.net/rsa/examples.html
     */


abstract class BaseEncryptionKey
    extends BaseAdapter
{
    protected $keyPair = false;

    abstract protected function loadKeyPair();

    static protected function generateKeyPair($keySize = 2048)
    {
        return new KeyPair($keySize);
    }

    private function getKeyPair()
    {
        if (!$this->loadKeyPair()) {
            return false;
        }
        return $this->keyPair;
    }

    public function encrypt($token)
    {
        $keyPair = $this->getKeyPair();
        if (!$keyPair) {
            return false;
        }
        return $keyPair->encrypt($token);
    }

    public function decrypt($encryptedToken)
    {
        $keyPair = $this->getKeyPair();
        if (!$keyPair) {
            return false;
        }
        return $keyPair->decrypt($encryptedToken);
    }

    public function isAvailable()
    {
        return $this->loadKeys() !== false;
    }

    static public function defaultConfig()
    {
        return array_merge(parent::defaultConfig(), [
            'keySize' => 2048
        ]);
    }

    static public function requiredConfig()
    {
        return array_merge(parent::requiredConfig(), ['keySize']);
    }
}
