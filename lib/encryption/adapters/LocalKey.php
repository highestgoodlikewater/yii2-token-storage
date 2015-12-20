<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;
use yii\base\InvalidConfigException;
use canis\tokenStorage\encryption\AdapterInterface;

/*
http://phpseclib.sourceforge.net/rsa/examples.html
 */

class LocalKey
    extends BaseEncryptionKey
{
    protected function loadKeys()
    {
        if ($this->publicKey && $this->privateKey) {
            return false;
        }
        $keyDirectory = Yii::getAlias($this->config['localStorage']);
        if ($keyDirectory === false) {
            throw new InvalidConfigException("The path alias ({$this->config['localStorage']}) for the local token encryption keys is invalid");
        }
        if (!is_dir($keyDirectory)) {
            @mkdir($keyDirectory, $this->config['keyPermissions'], true);
        }
        if (!is_dir($keyDirectory)) {
            throw new InvalidConfigException("The directory ({$keyDirectory}) for the local token encryption keys could not be protected");
        }
        @chmod($keyDirectory, $this->config['keyPermissions']);
        $privateKeyPath = $keyDirectory . DIRECTORY_SEPARATOR . $this->config['keyName'] . '.private';
        $publicKeyPath = $keyDirectory . DIRECTORY_SEPARATOR . $this->config['keyName'] . '.public';
        if (!is_readable($privateKeyPath) && !is_readable($publicKeyPath)) {
            $keys = $this->generateKeys();
            if ($keys && isset($keys['privatekey'])) {
                $this->privateKey = $keys['privatekey'];
                file_put_contents($privateKeyPath, $keys['privatekey']);
                chmod($privateKeyPath, $this->config['keyPermissions']);
            }
            if ($keys && isset($keys['publickey'])) {
                $this->publicKey = $keys['publickey'];
                file_put_contents($publicKeyPath, $keys['publickey']);
                chmod($publicKeyPath, $this->config['keyPermissions']);
            }
        } else {
            $this->privateKey = file_get_contents($privateKeyPath);
            $this->publicKey = file_get_contents($publicKeyPath);
        }
        if (!empty($this->privateKey) && !empty($this->publicKey)) {
            return true;
        }
        return false;
    }

    static public function defaultConfig()
    {
        return array_merge(static::defaultConfig(), [
            'localStorage' => '@runtime/keys',
            'keyName' => 'tokenKey',
            'keyPermissions' => '0600'
        ]);
    }

    static public function requiredConfig()
    {
        return array_merge(static::requiredConfig(), ['localStorage', 'keyName', 'keyPermissions']);
    }
}
