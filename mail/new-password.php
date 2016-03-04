<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\Users */


?>

<div class="password-reset">

    <p>Dear <?= Html::encode($email) ?></p>

    <p>We have received a request to reset your password. We have generated the new password for you:</p>

    <p><?=$password?></p>

    <p>Feel free to log in our system using your new password!</p>

    Best regards,

    BeaconCMS Team
</div>
