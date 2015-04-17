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
                                 'action'=>Url::to(['translation/create']),
                                 'fieldConfig' => [
                                     'template' => "<span class='left-mrg-10'>{input}\n{hint}\n{error}</span>",
                                     'inputTemplate' => '{input}',
                                 ],
                                ]); ?>
<?= $form->errorSummary($model);?>

<?= $form->field($model, 'source_message')->textInput(['placeholder'=>$model->getAttributeLabel('source_message'),'style'=>'width:300px;']) ?>
<?= $form->field($model, 'category')->textInput(['placeholder'=>$model->getAttributeLabel('category')]) ?>
<?= $form->field($model, 'translation')->textInput(['placeholder'=>$model->getAttributeLabel('translation'),'style'=>'width:300px;']) ?>
<?= $form->field($model, 'language')->hiddenInput(['style'=>'display:none']) ?>


    <?= Html::submitButton(Yii::t('app', '+') , ['class' =>  'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
<br>


