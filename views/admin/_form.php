<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.09.14
 * @time 19:46
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dkeeper\yii2\user\models\User $user
 * @var dkeeper\yii2\user\models\Profile $profile
 */
?>
<div class="user-form">

    <p><?= Yii::t("user", "Please fill out the following fields to register:") ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($user, 'email') ?>
    <?= $form->field($user, 'phone') ?>
    <?= $form->field($user, 'username') ?>
    <?= $form->field($user, 'newPassword')->passwordInput() ?>

    <?= $form->field($profile, 'first_name') ?>
    <?= $form->field($profile, 'last_name') ?>

    <?= $form->field($user, 'status')->dropDownList($user::statusDropdown()) ?>

    <?= Html::submitButton($user->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>