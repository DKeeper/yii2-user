<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 02.09.14
 * @time 19:25
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\components;

use yii\base\BootstrapInterface;
use dkeeper\yii2\user\helpers\ModuleTrait;

class User extends \yii\web\User implements BootstrapInterface
{
    use ModuleTrait;

    public $identityClass = 'dkeeper\yii2\user\models\User';

    /**
     * @inheritdoc
     */
    public $loginUrl = ["/user/login"];

    public function bootstrap($app)
    {
        $this->getModule()->addModuleRules();
    }

    /**
     * @inheritdoc
     */
    public function afterLogin($identity, $cookieBased, $duration)
    {
        /** @var \dkeeper\yii2\user\models\User $identity */
        $identity->afterLogin();
        parent::afterLogin($identity, $cookieBased, $duration);
    }

    /**
     * Get display name for the user
     *
     * @var string $default
     * @return string|int
     */
    public function getDisplayName($default = "")
    {
        /** @var \dkeeper\yii2\user\models\User $user */
        $user = $this->getIdentity();
        return $user ? $user->getDisplayName($default) : "";
    }
}
