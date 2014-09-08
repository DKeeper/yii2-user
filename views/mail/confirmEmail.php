<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \dkeeper\yii2\user\models\User $user
 * @var string $userKey
 */
?>

<h3><?= $subject ?></h3>

<p><?= Yii::t("user", "Please confirm your email address by clicking the link below:") ?></p>

<p><?= Url::toRoute(["/user/confirm", "key" => $userKey], true); ?></p>