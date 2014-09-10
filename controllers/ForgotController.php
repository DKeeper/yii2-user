<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.09.14
 * @time 19:47
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ForgotController extends Controller
{
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

    public function actionIndex(){
        /** @var $model \dkeeper\yii2\user\models\ForgotForm */
        $model = Yii::$app->getModule('user')->model('forgotForm');

        if($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()){
            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("Forgot-success", Yii::t("user", "Instructions to reset your password have been sent"));
        }

        return $this->render("index", [
            "model" => $model,
        ]);
    }
}
