<?php
namespace canisunit\tokenStorage;
use canis\tokenStorage\tokens\PlaintextToken;

class TokenTest extends TestCase
{
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
        $tokens = [
            ["pass\0word"],
            [chr(0)],
            ['привет, я multibyte...'],
            ['Qנטשופ צרכנות'],
            ["\x21?+"],
            ["\x21\x3F"]
        ];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        $tokens[] = [sha1(uniqid(rand(), true))];
        return $tokens;
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
