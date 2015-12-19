<?php
namespace canis\tokenStorage\encryption;

class AdapterInstance
    implements AdapterInstanceInterface
{
    private $id;
    private $adapter;

    public function getId()
    {
        return $this->id;
    }
}
