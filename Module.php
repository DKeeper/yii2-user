<?php

namespace dkeeper\yii2\user;

use Yii;
use yii\base\Application;
use \yii\helpers\ArrayHelper;

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

    public $resetKeyDuration = 3600; // 1 hour

    public $confirmKeyDuration = 3600;

    public $adminRole = "admin";

    public $urlRules = [
        '<a:(login|logout|register|confirm|forgot|reset|profile)>' => 'user/<a>',
        'profile/<a>' => 'user/profile/<a>',
    ];

    public $fieldRules = [];

    public $modelClasses = [];

    protected $_models = [];

    protected $_defaultControllerMap = [
        'login' => 'dkeeper\yii2\user\controllers\LoginController',
        'logout' => 'dkeeper\yii2\user\controllers\LogoutController',
        'register' => 'dkeeper\yii2\user\controllers\RegisterController',
        'confirm' => 'dkeeper\yii2\user\controllers\ConfirmController',
        'forgot' => 'dkeeper\yii2\user\controllers\ForgotController',
        'reset' => 'dkeeper\yii2\user\controllers\ResetController',
        'profile' => 'dkeeper\yii2\user\controllers\ProfileController',
        'admin' => 'dkeeper\yii2\user\controllers\AdminController',
    ];

    protected $_defaultModelClasses = [
        'user' => 'dkeeper\yii2\user\models\User',
        'profile' => 'dkeeper\yii2\user\models\Profile',
        'userKey' => 'dkeeper\yii2\user\models\UserKey',
        'loginForm' => 'dkeeper\yii2\user\models\forms\LoginForm',
        'forgotForm' => 'dkeeper\yii2\user\models\forms\ForgotForm',
        'userSearch' => 'dkeeper\yii2\user\models\search\UserSearch',
    ];

    protected $_defaultFieldRules = [
        'username' => [
            'type' => 'match',
            'pattern' => '/^[A-Za-z0-9_]+$/u',
            'message' => '{attribute} can contain only latin letters, numbers, and "_"',
        ],
        'phone' => [
            'type' => 'match',
            'pattern' => '/^\d+$/u',
            'message' => '{attribute} can contain only numbers',
        ],
    ];

    public function init()
    {
        parent::init();
        $this->controllerMap = ArrayHelper::merge(
            $this->_defaultControllerMap,
            $this->controllerMap
        );
        $this->modelClasses = ArrayHelper::merge(
            $this->_defaultModelClasses,
            $this->modelClasses
        );
        $this->fieldRules = ArrayHelper::merge(
            $this->_defaultFieldRules,
            $this->fieldRules
        );
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
