<?php
namespace canisunit\tokenStorage\adapters;

use Yii;
use canis\tokenStorage\encryption\KeyPair as KeyPairObject;
use canisunit\tokenStorage\TestCase;
use yii\helpers\FileHelper;
use canisunit\tokenStorage\TestTokensTrait;

class KeyPairTest extends TestCase
{
    use TestTokensTrait;

    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $keyDir = Yii::getAlias('@canisunit/tokenStorage/runtime/keys');
        if (is_dir($keyDir)) {
            FileHelper::removeDirectory($keyDir);
        }
    }

    // Tests :

    /**
     * Data provider for [[testCheckLocalKeyAdapter()]]
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
    public function testCheckKeyPair($secret)
    {
        $keyPair = new KeyPairObject(2048);
        $encrypted = $keyPair->encrypt($secret);
        $decrypted = $keyPair->decrypt($encrypted);
        $this->assertEquals($decrypted, $secret);
    }
}
