<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BeaconPins */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Beacon Pins',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Beacon Pins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="beacon-pins-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
