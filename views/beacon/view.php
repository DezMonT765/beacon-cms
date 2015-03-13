<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Beacons */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Beacons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacons-view">

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
                'description:text',
            [
              'label' => 'Picture',
              'value' => Html::img($model->getImage(),['width'=>250]),
              'format'=>'html'
            ],
            'place',
            'uuid',
            'minor',
            'major',
        ],
    ]) ?>

</div>
