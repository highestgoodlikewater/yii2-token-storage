<?php
namespace canis\tokenStorage\encryption\adapters;

use yii\base\InvalidConfigException;
use canis\tokenStorage\encryption\AdapterInterface;

    /*
    http://phpseclib.sourceforge.net/rsa/examples.html
     */


abstract class BaseAdapter
    implements AdapterInterface
{
    private $config;

    static public function requiredConfig()
    {
        return [];
    }

    static public function defaultConfig()
    {
        return [];
    }

    public function setConfig($config)
    {
        $currentConfig = [];
        if (isset($this->conifg)) {
            $currentConfig = $this->config;
        }
        $config = array_merge(static::defaultConfig(), $currentConfig, $config);
        foreach (static::requiredConfig() as $key) {
            if (!isset($config[$key])) {
                throw new InvalidConfigException(get_class($this) . " token encryption adapter requires '{$key}' config key");
            }
        }
        $this->config = $config;
    }

    public function getConfig()
    {
        if ($this->config === null) {
            $this->setConfig([]);
        }
        return $this->config;
    }
}
