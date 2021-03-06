<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var string $key
 * @var integer $type
 */
?>

<h3><?= $subject ?></h3>

<p><?= Yii::t("user", "Please confirm your email address by clicking the link below:") ?></p>

<p><?= Url::toRoute(["/user/confirm", "key" => $key, "type" => $type], true); ?></p>