<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BeaconPins */

$this->title = Yii::t('app', 'Create Beacon Pins');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Beacon Pins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacon-pins-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
