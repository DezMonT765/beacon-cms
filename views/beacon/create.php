<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Beacons */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Beacons',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Beacons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
