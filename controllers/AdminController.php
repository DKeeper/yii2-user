<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 11.09.14
 * @time 20:03
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use dkeeper\yii2\user\models\UserKey;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdminController extends Controller
{
    /**
     * @return \dkeeper\yii2\user\models\search\UserSearch
     */
    protected function getSearchModel()
    {
        return Yii::$app->getModule('user')->model('userSearch');
    }

    /**
     * @param $id string
     * @return \dkeeper\yii2\user\models\User
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        /** @var $_ \dkeeper\yii2\user\models\User */
        $_ = Yii::$app->getModule('user')->model('user');
        if (($model = $_::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function behaviors()
    {
        $adminRole = Yii::$app->getModule('user')->adminRole;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
//                        'roles' => [$adminRole],
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex(){
        $searchModel = $this->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(){
        /** @var $user \dkeeper\yii2\user\models\User */
        /** @var $profile \dkeeper\yii2\user\models\Profile */
        $user = Yii::$app->getModule('user')->model('user',["scenario" => "register"]);
        $profile = Yii::$app->getModule('user')->model('profile');

        if(Yii::$app->request->isAjax){
            $user->load(Yii::$app->request->post());
            $profile->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user, $profile);
        }

        if($user->load(Yii::$app->request->post()) && $user->validate() && $profile->load(Yii::$app->request->post())){
            $user->create_ip = Yii::$app->request->userIP;
            $user->setRegisterAttributes()->save(false);

            $profile->user_id = $user->id;

            if($profile->save()){
                if(!$user->email_confirm){
                    $userKey = UserKey::generate($user->id,UserKey::EMAIL_ACTIVATE,$user->getModule()->confirmKeyDuration);
                    $user->sendEmailConfirmation($userKey);
                }
                if(!$user->phone_confirm){
                    $userKey = UserKey::generate($user->id,UserKey::PHONE_ACTIVATE,$user->getModule()->confirmKeyDuration);
                    $user->sendPhoneConfirmation($userKey);
                }
                $this->redirect('/user/admin');
            }
        }

        return $this->render('create',[
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function actionUpdate($id){
        /** @var $user \dkeeper\yii2\user\models\User */
        /** @var $profile \dkeeper\yii2\user\models\Profile */
        $user = Yii::$app->getModule('user')->model('user');
        $user = $user::findOne($id);
        $profile = $user->profile;

        if(Yii::$app->request->isAjax){
            $user->load(Yii::$app->request->post());
            $profile->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user, $profile);
        }

        if($user->load(Yii::$app->request->post()) && $user->validate() && $profile->load(Yii::$app->request->post())){
            $user->setRegisterAttributes()->save(false);

            $profile->user_id = $user->id;

            if($profile->save()){
                if(!$user->email_confirm){
                    $userKey = UserKey::generate($user->id,UserKey::EMAIL_ACTIVATE,$user->getModule()->confirmKeyDuration);
                    $user->sendEmailConfirmation($userKey);
                }
                if(!$user->phone_confirm){
                    $userKey = UserKey::generate($user->id,UserKey::PHONE_ACTIVATE,$user->getModule()->confirmKeyDuration);
                    $user->sendPhoneConfirmation($userKey);
                }
                $this->redirect('/user/admin');
            }
        }

        return $this->render('update',[
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
