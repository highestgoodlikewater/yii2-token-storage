<?php
namespace canis\tokenStorage\tokens;

use canis\tokenStorage\encryption\AdapterInstance;

abstract class BaseToken
    implements TokenInterface
{
    private $adapterInstance;

    /**
     * Convert adapter instance to string
     * @return array [description]
     */
    public function __sleep()
    {
        if (is_object($this->adapterInstance)) {
            $this->adapterInstance = $this->adapterInstance->getId();
        }
    }

    /**
     * Constructor for tokens
     * @param  string                       $token      String that needs to be stored
     * @param  AdapterInstanceInterface     $adapter
     */
    public function __construct($token, AdapterInstanceInterface $adapterInstance = null)
    {
        $this->adapterInstance = $adapterInstance;
        $this->initializedToken($token);
    }

    protected function getAdapterInstance()
    {
        return $this->adapterInstance;
    }

    /**
     * Convert object to string
     * @return string token string
     */
    public function __toString()
    {
        return (string)$this->getToken();
    }
}
