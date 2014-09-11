<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.09.14
 * @time 19:53
 * Created by JetBrains PhpStorm.
 */

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dkeeper\yii2\user\models\forms\ForgotForm $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Forgot password');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-forgot">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($flash = Yii::$app->session->getFlash('Forgot-success')): ?>

    <div class="alert alert-success">
        <p><?= $flash ?></p>
    </div>

    <?php else: ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'forgot-form']); ?>
            <?= $form->field($model, 'search') ?>
            <?php
                $options = ['username' => Yii::t('user', 'Username')];
                if($model->getModule()->loginEmail) $options['email'] = Yii::t('user', 'Email');
                if($model->getModule()->loginPhone) $options['phone'] = Yii::t('user', 'Phone');
            ?>
            <?= $form->field($model, 'type')->dropDownList($options) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php endif; ?>

</div>