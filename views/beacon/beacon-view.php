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
        <?if(Yii::$app->user->can(\app\commands\RbacController::delete_beacon)):?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?endif?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
                'title:raw',
                'description:html',
            [
                'attribute'=>'picture',
                'value' => Html::img($model->getFile('picture'),['width'=>250]),
                'format'=>'html'
            ],
                [
                    'attribute'=>'horizontal_picture',
                    'value' => Html::img($model->getFile('horizontal_picture'),['width'=>250]),
                    'format'=>'html'
                ],
            'place',
            'uuid',
            'minor',
            'major',
        ],
    ]) ?>

</div>
