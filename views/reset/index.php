<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.09.14
 * @time 21:53
 * Created by JetBrains PhpStorm.
 */

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dkeeper\yii2\user\models\User $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Forgot password');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-reset">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ( ($flash = Yii::$app->session->getFlash('Reset-invalid-key')) || ($flash = Yii::$app->session->getFlash('Reset-expired-key'))){ ?>

    <div class="alert alert-danger">
        <p><?= $flash ?></p>
    </div>

    <?php } elseif ($flash = Yii::$app->session->getFlash('Reset-success')) { ?>

    <div class="alert alert-success">
        <p><?= $flash ?></p>
    </div>

    <?php } else { ?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'forgot-form']); ?>
            <?= $form->field($model, 'newPassword')->passwordInput() ?>
            <?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php } ?>

</div>