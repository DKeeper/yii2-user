<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 08.09.14
 * @time 21:55
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ConfirmController extends Controller
{
    public function actionIndex($key)
    {
        /** @var $model \dkeeper\yii2\user\models\User */
        $model = Yii::$app->getModule('user')->model('user');
        $model = $model::find()->where(['auth_key'=>$key]);

        $success = false;

        if($model){
            $success = true;
            $model->confirm('email');
        }

        return $this->render('index',['success'=>$success]);
    }
}
