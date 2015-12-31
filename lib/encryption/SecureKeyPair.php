<?php
namespace canis\tokenStorage\encryption;

use phpseclib\Crypt\RSA as Crypt_RSA;

class SecureKeyPair
    extends BaseKeyPair
    implements SecureKeyPairInterface
{
    private $keyPair;

    public function __construct($keySize = 2048)
    {
        $this->keyPair = new KeyPair($keySize);
    }

    public function __sleep()
    {
        if (!$this->isEncrypted()) {
            // @todo figure out exception for this
            throw new \Exception("Trying to serialize an unlocked secure keypair");
        }
    }

    public function isEncrypted()
    {
        return is_string($this->keyPair);
    }

    public function unlock(KeyPair $keyPair)
    {
        if (!isset($this->keyPair)) {
            return false;
        }
        
        if (is_object($this->keyPair)) {
            return true;
        }

        $decryptedKeyPair = $keyPair->decrypt($this->keyPair);
        if (!$decryptedKeyPair) {
            return false;
        }
        $keyPair = unserialize($decryptedKeyPair);
        if (!$keyPair || !($keyPair instanceof KeyPairInterface)) {
            return false;
        }
        $this->keyPair = $keyPair;
        return true;
    }

    public function lock(KeyPair $keyPair)
    {
        if (!is_object($this->keyPair)) {
            return false;
        }
        $encryptedKeyPair = $keyPair->encrypt(serialize($this->keyPair));
        if (!$encryptedKeyPair) {
            return false;
        }
        $this->keyPair = $encryptedKeyPair;
        return true;
    }

    public function encrypt($token)
    {
        if ($this->isEncrypted()) {
            return false;
        }
        return $this->keyPair->encrypt($token);
    }

    public function decrypt($encryptedToken)
    {
        if ($this->isEncrypted()) {
            return false;
        }
        return $this->keyPair->decrypt($encryptedToken);
    }
}
