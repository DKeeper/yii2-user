<?php

namespace dkeeper\yii2\user;

use Yii;
use yii\base\Application;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'dkeeper\yii2\user\controllers';

    public $requireEmail = true;

    public $requireUsername = true;

    public $requirePhone = true;

    public $loginEmail = true;

    public $loginUsername = true;

    public $loginPhone = true;

    public $emailConfirmation = true;

    public $phoneConfirmation = false;

    public $loginDuration = 86400; // 1 day

    public $activateKeyDuration = 86000;

    public $resetKeyDuration = 3600; // 1 hour

    public $confirmKeyDuration = 3600;

    public $adminRole = "admin";

    public $fieldRules = [
        'username' => [
            'type' => 'match',
            'pattern' => '/^[A-Za-z0-9_]+$/u',
            'message' => '{attribute} can contain only latin letters, numbers, and "_"',
        ],
        'phone' => [
            'type' => 'match',
            'pattern' => '/^\+\d+$/u',
            'message' => '{attribute} can contain only "+" and numbers',
        ],
    ];

    protected $_models = [];

    public $controllerMap = [
        'login' => 'dkeeper\yii2\user\controllers\LoginController',
        'logout' => 'dkeeper\yii2\user\controllers\LogoutController',
        'register' => 'dkeeper\yii2\user\controllers\RegisterController',
        'confirm' => 'dkeeper\yii2\user\controllers\ConfirmController',
    ];

    public $modelClasses = [
        'user' => 'dkeeper\yii2\user\models\User',
        'loginForm' => 'dkeeper\yii2\user\models\LoginForm',
    ];

    public $urlRules = [
        '<a:(login|logout|register|profile)>' => 'user/<a>',
    ];

    public function init()
    {
        parent::init();
        if($this->loginEmail) $this->requireEmail = true;
        if($this->loginUsername) $this->requireUsername = true;
        if($this->loginPhone) $this->requirePhone = true;

        if (empty(Yii::$app->i18n->translations['user'])) {
            Yii::$app->i18n->translations['user'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }

    public function addModuleRules(){
        if(!empty($this->urlRules)) Yii::$app->urlManager->addRules($this->urlRules,false);
    }

    public function model($name){
        if(!empty($this->_models[$name])){
            return $this->_models[$name];
        }

        $className = $this->modelClasses[$name];
        $this->_models[$name] = Yii::createObject(array_merge(["class" => $className]));
        return $this->_models[$name];
    }
}
