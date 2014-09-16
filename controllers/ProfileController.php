<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 15.09.14
 * @time 23:09
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'index' => ['post'],
//                ],
//            ],
        ];
    }

    public function actionIndex()
    {
        /** @var $profile \dkeeper\yii2\user\models\Profile */
        $profile = Yii::$app->user->profile;

        // redirect
        return $this->render('index', [
            'profile' => $profile,
        ]);
    }

    public function actionUpdate(){
        /** @var $profile \dkeeper\yii2\user\models\Profile */
        $profile = Yii::$app->user->profile;

        if($profile->load(Yii::$app->request->post()) && $profile->save()){
            return $this->redirect(['/user/profile']);
        } else {
            // redirect
            return $this->render('update', [
                'profile' => $profile,
            ]);
        }
    }
}
