<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 02.09.14
 * @time 19:25
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\components;

use yii\base\BootstrapInterface;

class User extends \yii\web\User implements BootstrapInterface
{
    public $identityClass = 'dkeeper\yii2\user\models\User';

    /**
     * @inheritdoc
     */
    public $loginUrl = ["/user/login"];

    public function bootstrap($app)
    {
        \Yii::$app->getModule('user')->addModuleRules();
    }

    /**
     * Get display name for the user
     *
     * @var string $default
     * @return string|int
     */
    public function getDisplayName($default = "")
    {
        // define possible fields
        $possibleNames = [
            "username",
            "email",
            "id",
        ];

        // go through each and return if valid
        foreach ($possibleNames as $possibleName) {
            if (!empty($this->$possibleName)) {
                return $this->$possibleName;
            }
        }

        return $default;
    }
}
