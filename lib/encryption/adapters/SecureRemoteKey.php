<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;
use yii\helpers\FileHelper;

abstract class SecureRemoteKey
    extends BaseRemoteKey
{
    private $masterKeyPair;

    abstract protected function loadRemoteKeyPair();

    private function getMasterKeyPair()
    {
        if (!$this->loadMasterKeys()) {
            return false;
        }
        return $this->masterKeyPair;
    }

    protected function loadKeyPair()
    {
        if ($this->keyPair !== null) {
            return true;
        }

        if (!static::loadRemoteKeyPair()) {
            return false;
        }
        $masterKeyPair = $this->getMasterKeyPair();
        if (!$masterKeyPair) {
            return false;
        }
        // @todo unlock remote key
        return true;
    }

    private function loadMasterKeys()
    {
        if ($this->masterKeyPair) {
            return true;
        }
        $config = $this->getConfig();
        $keyDirectory = Yii::getAlias($config['localStorage']);
        if ($keyDirectory === false) {
            throw new InvalidConfigException("The path alias ({$config['localStorage']}) for the master encryption keys is invalid");
        }
        if (!is_dir($keyDirectory)) {
            FileHelper::createDirectory($keyDirectory, $config['masterKeyDirectoryPermissions'], true);
        }
        if (!is_dir($keyDirectory)) {
            throw new InvalidConfigException("The directory ({$keyDirectory}) for the master encryption keys could not be protected");
        }
        chmod($keyDirectory, $config['masterKeyDirectoryPermissions']);
        $keyPath = $keyDirectory . DIRECTORY_SEPARATOR . $config['masterKeyName'] . '.key';
        if (!is_readable($keyPath)) {
            $keyPair = static::generateKeyPair($config['keySize']);
            if ($keyPair) {
                $this->masterKeyPair = $keyPair;
                file_put_contents($keyPath, serialize($keyPair));
                if (!file_exists($keyPath)) {
                    return false;
                }
                chmod($keyPath, $config['masterKeyPermissions']);
            }
        } else {
            $this->masterKeyPair = unserialize(file_get_contents($keyPath));
        }
        if (!empty($this->keyPair) && $this->keyPair instanceof KeyPairInterface) {
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
