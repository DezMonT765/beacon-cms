<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Groups',
]) . ' ' . $model->id;

?>
<div class="groups-update">

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
