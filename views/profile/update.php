<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.09.14
 * @time 19:31
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $profile dkeeper\yii2\user\models\Profile */

$this->title = Yii::t('user', 'Update {modelClass}', [
    'modelClass' => Yii::t('user','Profile'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User'), 'url' => ['user/profile']];;
$this->params['breadcrumbs'][] = $profile->user->displayName;

$this->title .= ' ' . $profile->user->displayName;
?>
<div class="profile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="profile-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($profile, 'first_name')->textInput() ?>

        <?= $form->field($profile, 'last_name')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($profile->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $profile->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>