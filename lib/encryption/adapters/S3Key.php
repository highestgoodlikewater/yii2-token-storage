<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;
use yii\base\InvalidConfigException;
use canis\tokenStorage\encryption\AdapterInterface;

/*
http://phpseclib.sourceforge.net/rsa/examples.html
 */

class S3Key
    extends SecureRemoteKey
{
    

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
