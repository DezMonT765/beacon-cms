<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
\app\assets\Select2Asset::register($this);
?>

<?php $form = ActiveForm::begin(['layout'=>'inline',
                                 'action'=>Url::to(['translation/load']),
                                 'fieldConfig' => [
                                     'template' => "<span class='left-mrg-10'>{input}\n{hint}\n{error}</span>",
                                     'inputTemplate' => '{input}',
                                 ],
                                 'options' => [
                                     'enctype' => 'multipart/form-data'
                                 ]
                                ]); ?>
<?= $form->errorSummary($model);?>

<?= $form->field($model, 'file')->fileInput(['placeholder'=>$model->getAttributeLabel('file')]) ?>
<?= $form->field($model, 'language')->hiddenInput(['style'=>'display:none']) ?>


    <?= Html::submitButton(Yii::t('app', "<span class='glyphicon glyphicon-upload'></span>") , ['class' =>  'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
<br>


