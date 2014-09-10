<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var string $key
 * @var string $type
 */
?>

<h3><?= $subject ?></h3>

<p><?= Yii::t("user", "Please use this link to reset your password:") ?></p>

<p><?= Url::toRoute(["/user/reset", "key" => $key, "type" => $type], true); ?></p>
