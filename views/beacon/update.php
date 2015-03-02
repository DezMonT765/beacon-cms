<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Beacons */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Beacons',
]) . ' ' . $model->title;

?>
<div class="beacons-update">

   <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
