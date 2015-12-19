<?php
namespace canis\tokenStorage\encryption;

interface AdapterInterface
{
    public function encrypt(string $token);
    public function decrypt(string $encryptedToken);
    public function isAvailable();
}
