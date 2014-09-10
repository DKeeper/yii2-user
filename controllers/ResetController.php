<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.09.14
 * @time 21:34
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ResetController extends Controller
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

    public function actionIndex($key,$type)
    {
        /** @var $model \dkeeper\yii2\user\models\UserKey */
        $model = Yii::$app->getModule('user')->model('userKey');
        $model = $model::find()->where(['key_value'=>$key,'type'=>$type])->one();

        if(!$model){
            $user = null;
            Yii::$app->session->setFlash("Reset-invalid-key", Yii::t("user", "Invalid key"));
        } elseif ($model->consume_to < time()) {
            $user = null;
            Yii::$app->session->setFlash("Reset-expired-key", Yii::t("user", "Expired key"));
        } else {
            /** @var $user \dkeeper\yii2\user\models\User */
            $user = $model->user;
            $user->scenario = 'reset';
            if($user->load(Yii::$app->request->post()) && $user->save()){
                $model->consume();
                Yii::$app->session->setFlash("Reset-success", Yii::t("user", "Password was changed successful"));
            }
        }

        return $this->render('index',['model'=>$user]);
    }
}
