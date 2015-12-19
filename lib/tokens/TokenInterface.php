<?php
namespace canis\tokenStorage\tokens;

interface TokenInterface
{
    /**
     * Returns the plaintext token
     * @return string Plaintext token
     */
    public function getToken();

    /**
     * Initialize an interface with a token
     * @param  string $token Token string
     */
    public function initializedToken(string $token);

    /**
     * Checks if the token has been initialized
     * @return boolean Initialization status of the token
     */
    public function isInitialized();
}
