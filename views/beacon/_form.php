<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Beacons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="beacons-form">

    <?php $form = ActiveForm::begin([
                                    'id'=>'beacon-form',
                                    'enableAjaxValidation' => false,
                                    'enableClientValidation' => true,
                                    'options'=>['enctype'=>'multipart/form-data']
                                    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'picture')->fileInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'place')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'minor')->textInput() ?>

    <?= $form->field($model, 'major')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
