<?php
/**
 * @author: Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date: 02.09.14
 * @time: 15:19
 * Created by PhpStorm.
 */

namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RegisterController extends Controller {

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
        /** @var $user \dkeeper\yii2\user\models\User */
        $user = Yii::$app->getModule("user")->model("user", ["scenario" => "register"]);
        if ($user->load(Yii::$app->request->post())) {

            // validate for ajax request
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }

            // validate for normal request
            if ($user->validate()) {

                $user->create_ip = Yii::$app->request->userIP;

                $user->setRegisterAttributes()->save(false);

                if(!$user->email_confirm){
                    $user->sendEmailConfirmation();
                }
                if(!$user->phone_confirm){
                    $user->sendPhoneConfirmation();
                }

                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                $successText = Yii::t("user", "Successfully registered [ {displayName} ]", ["displayName" => $user->getDisplayName()]);
                $guestText = "";
                if (Yii::$app->user->isGuest) {
                    $guestText = Yii::t("user", " - Please check your email to confirm your account");
                }
                Yii::$app->session->setFlash("Register-success", $successText . $guestText);
            }
        }
        return $this->render('index',['user' => $user]);
    }

}