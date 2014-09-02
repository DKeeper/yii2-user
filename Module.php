<?php

namespace dkeeper\yii2\user;

use Yii;
use yii\base\Application;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'dkeeper\yii2\user\controllers';

    public $requireEmail = true;

    public $requireUsername = true;

    public $loginEmail = true;

    public $loginUsername = true;

    public $loginDuration = 86400; // 1 day

    public $activateKeyDuration = 86000;

    public $resetKeyDuration = 3600; // 1 hour

    public $controllerMap = [
        'login' => 'dkeeper\yii2\user\controllers\LoginController',
        'logout' => 'dkeeper\yii2\user\controllers\LogoutController',
        'register' => 'dkeeper\yii2\user\controllers\RegisterController',
    ];

    public $urlRules = [
        ['<a:(login)>' => 'user/<a>'],
    ];

    public function __construct($id, $parent = null, $config = []){
        parent::__construct($id, $parent = null, $config = []);
    }

    public function init()
    {
        parent::init();
    }
}
