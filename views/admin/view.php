<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 11.09.14
 * @time 22:35
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \dkeeper\yii2\user\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User'), 'url' => ['user/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    </p>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'status',
        'username:text',
        'email:email',
        'phone:text',
        'auth_key:text',
        'last_login:datetime',
        'login_ip:text',
        'created_at:datetime',
        'create_ip:text',
        'updated_at:datetime',
        'ban_to:datetime',
        'ban_reason:text',
    ],
]) ?>

</div>