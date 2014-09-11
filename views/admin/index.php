<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 11.09.14
 * @time 22:07
 * Created by JetBrains PhpStorm.
 */

/* @var $this yii\web\View */
/* @var $searchModel dkeeper\yii2\user\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;

$this->title = Yii::t('user', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$statusFilter = [
    $searchModel::INACTIVE => Yii::t('user','Inactive'),
    $searchModel::ACTIVE => Yii::t('user','Active'),
    $searchModel::UNCONFIRMED_EMAIL => Yii::t('user','Unconfirmed email'),
    $searchModel::UNCONFIRMED_PHONE => Yii::t('user','Unconfirmed phone'),
];
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('user', 'Create {modelClass}', [
        'modelClass' => Yii::t('user', 'User'),
    ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'username:ntext',
        'email:ntext',
        'phone:ntext',
        [
            'class' => DataColumn::className(),
            'attribute' => 'status',
            'value' => function($model, $key, $index){
                switch($model->status){
                    case $model::ACTIVE:
                        $_ = Yii::t('user','Active');
                        break;
                    case $model::UNCONFIRMED_EMAIL:
                        $_ = Yii::t('user','Unconfirmed email');
                        break;
                    case $model::UNCONFIRMED_PHONE:
                        $_ = Yii::t('user','Unconfirmed phone');
                        break;
                    default:
                        $_ = Yii::t('user','Inactive');
                        break;
                }
                return $_;
            },
            'filter' => $statusFilter
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

</div>