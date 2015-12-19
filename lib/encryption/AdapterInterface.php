<?php
namespace canis\tokenStorage\encryption;

interface AdapterInterface
{
    public function encrypt($token);
    public function decrypt($encryptedToken);
    public function isAvailable();
}
