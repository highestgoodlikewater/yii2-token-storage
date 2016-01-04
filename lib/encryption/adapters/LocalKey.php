<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use canis\tokenStorage\encryption\AdapterInterface;
use canis\tokenStorage\encryption\KeyPairInterface;

/*
http://phpseclib.sourceforge.net/rsa/examples.html
 */

class LocalKey
    extends BaseEncryptionKey
{
    protected function loadKeyPair()
    {
        if ($this->keyPair !== null) {
            return true;
        }
        $config = $this->getConfig();
        $keyDirectory = Yii::getAlias($config['localStorage']);
        if ($keyDirectory === false) {
            throw new InvalidConfigException("The path alias ({$config['localStorage']}) for the local token encryption keys is invalid");
        }
        if (is_string($keyDirectory) && !is_dir($keyDirectory)) {
            FileHelper::createDirectory($keyDirectory, $config['keyDirectoryPermissions'], true);
        }
        if (!is_dir($keyDirectory)) {
            throw new InvalidConfigException("The directory ({$keyDirectory}) for the local token encryption keys could not be protected");
        }
        chmod($keyDirectory, $config['keyDirectoryPermissions']);
        $keyPath = $keyDirectory . DIRECTORY_SEPARATOR . $config['keyName'] . '.key';
        if (!is_readable($keyPath)) {
            $keyPair = static::generateKeyPair($config['keySize']);
            if ($keyPair) {
                $this->keyPair = $keyPair;
                file_put_contents($keyPath, serialize($keyPair));
                if (!file_exists($keyPath)) {
                    return false;
                }
                chmod($keyPath, $config['keyPermissions']);
            }
        } else {
            $this->keyPair = unserialize(file_get_contents($keyPath));
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
            'keyName' => 'tokenKey',
            'keyPermissions' => 0600,
            'keyDirectoryPermissions' => 0777 // @todo check this out
        ]);
    }

    static public function requiredConfig()
    {
        return array_merge(parent::requiredConfig(), ['localStorage', 'keyName', 'keyPermissions', 'keyDirectoryPermissions']);
    }
}
