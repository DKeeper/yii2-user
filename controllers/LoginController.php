<?php
/**
 * @author: Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date: 02.09.14
 * @time: 15:07
 * Created by PhpStorm.
 */

namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class LoginController extends Controller {

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        /** @var $model \dkeeper\yii2\user\models\LoginForm */
        $model = Yii::$app->getModule('user')->model('loginForm');

        if ($model->load(Yii::$app->request->post()) && $model->login(Yii::$app->getModule("user")->loginDuration)) {
            return $this->goHome();
        }

        return $this->render('index',['model'=>$model]);
    }
} 