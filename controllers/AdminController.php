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
}
