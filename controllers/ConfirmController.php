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
use dkeeper\yii2\user\models\UserKey;

class ConfirmController extends Controller
{
    public function actionIndex($key,$type)
    {
        /** @var $model \dkeeper\yii2\user\models\UserKey */
        $model = Yii::$app->getModule('user')->model('userKey');
        $model = $model::find()->where(['key_value'=>$key,'type'=>$type])->one();

        $success = false;

        if($model){
            if($type == UserKey::EMAIL_ACTIVATE && $model->user->getModule()->emailConfirmation && $model->user->email_confirm){
                return $this->goHome();
            }
            $model->user->confirm('email');
            $model->consume();
            $success = $model->user->email;
        }

        return $this->render('index',['success'=>$success]);
    }
}
