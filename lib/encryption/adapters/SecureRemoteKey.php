<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;

abstract class SecureRemoteKey
    extends BaseEncryptionKey
{
    private $masterPublicKey;
    private $masterPrivateKey;

    private function getMasterKeyPair()
    {
        if (!$this->loadMasterKeys()) {
            return false;
        }
        return [
            'private' => $this->masterPrivateKey,
            'public' => $this->masterPublicKey
        ];
    }

    protected function loadKeys()
    {
        if ($this->publicKey && $this->privateKey) {
            return true;
        }
        $keyPair = $this->getMasterKeyPair();
        if (!$keyPair) {
            return false;
        }
        
    }

    private function loadMasterKeys()
    {
        if ($this->masterPublicKey && $this->masterPrivateKey) {
            return true;
        }
        $config = $this->getConfig();
        $keyDirectory = Yii::getAlias($config['localStorage']);
        if ($keyDirectory === false) {
            throw new InvalidConfigException("The path alias ({$config['localStorage']}) for the master encryption keys is invalid");
        }
        if (!is_dir($keyDirectory)) {
            @mkdir($keyDirectory, $config['masterKeyDirectoryPermissions'], true);
        }
        if (!is_dir($keyDirectory)) {
            throw new InvalidConfigException("The directory ({$keyDirectory}) for the master encryption keys could not be protected");
        }
        @chmod($keyDirectory, $config['masterKeyDirectoryPermissions']);
        $privateKeyPath = $keyDirectory . DIRECTORY_SEPARATOR . $config['masterKeyName'] . '.private';
        $publicKeyPath = $keyDirectory . DIRECTORY_SEPARATOR . $config['masterKeyName'] . '.public';
        if (!is_readable($privateKeyPath) || !is_readable($publicKeyPath)) {
            $keys = $this->generateKeys();
            if ($keys && isset($keys['privatekey'])) {
                $this->masterPrivateKey = $keys['privatekey'];
                file_put_contents($privateKeyPath, $keys['privatekey']);
                chmod($privateKeyPath, $config['masterKeyPermissions']);
            }
            if ($keys && isset($keys['publickey'])) {
                $this->masterPublicKey = $keys['publickey'];
                file_put_contents($publicKeyPath, $keys['publickey']);
                chmod($publicKeyPath, $config['masterKeyPermissions']);
            }
        } else {
            $this->masterPrivateKey = file_get_contents($privateKeyPath);
            $this->masterPublicKey = file_get_contents($publicKeyPath);
        }
        if (!empty($this->masterPrivateKey) && !empty($this->masterPublicKey)) {
            return true;
        }
        return false;
    }

    static public function defaultConfig()
    {
        return array_merge(parent::defaultConfig(), [
            'localStorage' => '@runtime/keys',
            'masterKeyName' => 'masterKey',
            'masterKeyPermissions' => 0600,
            'masterKeyDirectoryPermissions' => 0777 // @todo check this out
        ]);
    }

    static public function requiredConfig()
    {
        return array_merge(parent::requiredConfig(), ['localStorage', 'masterKeyName', 'masterKeyPermissions', 'masterKeyDirectoryPermissions']);
    }
}
