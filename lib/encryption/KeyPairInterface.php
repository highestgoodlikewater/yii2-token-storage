<?php
namespace canis\tokenStorage\encryption;

interface KeyPairInterface
{
    public function encrypt($token);
    public function decrypt($encryptedToken);
}
