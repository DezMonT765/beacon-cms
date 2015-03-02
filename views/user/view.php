<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->name;
?>
<div class="users-view">

    <br>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'email:email',
        ],
    ]) ?>

</div>
