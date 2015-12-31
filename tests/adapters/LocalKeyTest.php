<?php
namespace canisunit\tokenStorage\adapters;

use Yii;
use canis\tokenStorage\encryption\adapters\LocalKey as EnryptionAdapter;
use canisunit\tokenStorage\TestCase;
use yii\helpers\FileHelper;
use canisunit\tokenStorage\TestTokensTrait;

class LocalKeyTest extends TestCase
{
    use TestTokensTrait;
    private $adapter;

    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
        $config = ['class' => EnryptionAdapter::class];
        $this->adapter = Yii::createObject($config);
        $this->adapter->setConfig(['keyName' => 'testKey-' . microtime(true)]);
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
    public function testCheckLocalKeyAdapter($secret)
    {
        $encrypted = $this->adapter->encrypt($secret);
        $decrypted = $this->adapter->decrypt($encrypted);
        $this->assertEquals($decrypted, $secret);
    }
}
