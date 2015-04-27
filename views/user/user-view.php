<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->name;
?>
<div class="users-view">

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
            'name',
            'email:email',
        ],
    ]) ?>
    <legend><?php echo Yii::t('user',':groups')?></legend>
    <?= \yii\widgets\ListView::widget([
        'dataProvider'=>$model->getGroupsProvider(),
        'itemView' => 'small-group'

    ])?>
</div>
