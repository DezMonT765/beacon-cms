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
    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
                'title:raw',
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