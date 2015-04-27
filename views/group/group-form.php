<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-form">

    <br>

    <fieldset class="col-md-6">
        <legend><?php echo Yii::t('group', ':group_settings')?></legend>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'description')->textarea() ?>
    </fieldset>
    <fieldset class="col-md-6">

        <legend><?php echo Yii::t('group', ':beacon_default_content')?></legend>
        <?= $form->field($model, 'uuid')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'major')->textInput() ?>
        <?= $form->field($model, 'minor')->textInput() ?>
        <?= $form->field($model, 'place')->textInput() ?>
    </fieldset>
    <fieldset class="col-md-12">

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', ':create') : Yii::t('app', ':create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </fieldset>

</div>
