<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 15.09.14
 * @time 23:13
 * Created by JetBrains PhpStorm.
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var dkeeper\yii2\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User'), 'url' => ['user/view',['id'=>$profile->user->id]]];;
$this->params['breadcrumbs'][] = $profile->user->displayName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('user', 'Update {modelClass}', [
        'modelClass' => Yii::t('user','Profile'),
    ]), ['profile/update'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
    'model' => $profile,
    'attributes' => [
        'id',
        [
            'label' => $profile->getAttributeLabel('user_id'),
            'value' => $profile->user->displayName,
        ],
        'first_name',
        'last_name',
    ],
]) ?>

</div>