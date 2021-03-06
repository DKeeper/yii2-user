<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dkeeper\yii2\user\models\User $user
 */

$this->title = Yii::t('user', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-register">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($flash = Yii::$app->session->getFlash("Register-success")): ?>

    <div class="alert alert-success">
        <p><?= $flash ?></p>
    </div>

    <?php else: ?>

    <p><?= Yii::t("user", "Please fill out the following fields to register:") ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
        'enableAjaxValidation' => true,
    ]); ?>

    <?php if ($user->getModule()->requireEmail): ?>
        <?= $form->field($user, 'email') ?>
        <?php endif; ?>

    <?php if ($user->getModule()->requirePhone): ?>
        <?= $form->field($user, 'phone') ?>
        <?php endif; ?>

    <?php if ($user->getModule()->requireUsername): ?>
        <?= $form->field($user, 'username') ?>
        <?php endif; ?>

    <?= $form->field($user, 'newPassword')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('user', 'Register'), ['class' => 'btn btn-primary']) ?>

            <?= Html::a(Yii::t('user', 'Login'), ["/user/login"]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php endif; ?>

</div>