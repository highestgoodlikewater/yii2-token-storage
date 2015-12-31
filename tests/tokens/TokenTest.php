<?php
namespace canisunit\tokenStorage\tokens;

use canis\tokenStorage\tokens\PlaintextToken;
use canisunit\tokenStorage\TestCase;
use canisunit\tokenStorage\TestTokensTrait;

class TokenTest extends TestCase
{
    use TestTokensTrait;
    
    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    // Tests :

    /**
     * Data provider for [[testCheckTokens()]]
     * @return array test data
     */
    public function dataProviderCheckTokens()
    {
        return static::testTokens();
    }

    /**
     * @dataProvider dataProviderCheckTokens
     *
     * @param string $secret
     * @param string $expectedResult
     */
    public function testCheckPlaintextTokens($secret)
    {
        $token = new PlaintextToken($secret);
        $this->assertEquals($secret, $this->invoke($token, 'getToken'));
        $this->assertEquals($secret, (string)$token);
    }
}
