<?php
namespace canis\tokenStorage\encryption\adapters;

use Yii;

abstract class BaseRemoteKey
    extends BaseEncryptionKey
{
    abstract protected function loadRemoteKeyPair();

    static public function defaultConfig()
    {
        return array_merge(parent::defaultConfig(), []);
    }

    static public function requiredConfig()
    {
        return array_merge(parent::requiredConfig(), []);
    }
}
