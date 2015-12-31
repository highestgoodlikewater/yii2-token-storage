<?php
namespace canis\tokenStorage\encryption;

interface SecureKeyPairInterface
{
    public function isEncrypted();
    public function unlock(KeyPair $keyPair);
    public function lock(KeyPair $keyPair);
}
