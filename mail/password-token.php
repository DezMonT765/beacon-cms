<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\Users */


?>

<div class="password-reset">

    <p>Dear <?= Html::encode($user_name) ?></p>

    <p>We have received a request to reset your password. If you have initiated this request, please click on the following link to complete the process:</p>

    <p><?= Html::a(Html::encode($password_reset_link), $password_reset_link) ?></p>

    In case you have not initiated this request, please ignore this message.

    Best regards,

    BeaconCMS Team


</div>
