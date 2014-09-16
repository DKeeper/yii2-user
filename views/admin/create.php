<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.09.14
 * @time 19:46
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RoomPrices */
/* @var dkeeper\yii2\user\models\User $user */
/* @var dkeeper\yii2\user\models\Profile $profile */

$this->title = Yii::t('user', 'Create {modelClass}', [
    'modelClass' => Yii::t('user', 'User'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['user/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'user' => $user,
    'profile' => $profile,
]) ?>

</div>