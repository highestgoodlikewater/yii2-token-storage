<?php
namespace canis\tokenStorage\tokens;

class PlaintextToken
    extends BaseToken
{
    /**
     * @var string Plaintext token
     */
    private $token;

    /**
     * @inheritdoc
     */
    public function initializedToken($token)
    {
        $this->token = $token;
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function isInitialized()
    {
        return $this->token !== null;
    }
}
