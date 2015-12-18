<?php
namespace canis\tokenStorage;

use Yii;

class Extension implements \yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@canis/tokenStorage', __DIR__);
        $app->set('tokenStorage', ['class' => Component::className()]);
    }
}