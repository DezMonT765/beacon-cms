<?php

use app\models\Languages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
\app\assets\Select2Asset::register($this);
?>

<div class="users-form">
    <br>
    <div class="col-md-6">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 50]) ?>
    <?php if ($model->isNewRecord):?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
    <?php endif?>
        <?php if(Yii::$app->user->can(\app\commands\RbacController::admin)):?>
    <?= $form->field($model, 'groupsToBind')->textInput(['class'=>'']) ?>
        <?php endif?>
    <?= $form->field($model, 'role')->dropDownList(Yii::$app->user->identity->getEditableRoles($model->id)) ?>
        <?= $form->field($model, 'language')->dropDownList(Languages::getLanguageNames(true)) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user', ':create') : Yii::t('user', ':update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>

</div>
<script>
    initSelect($('#<?= Html::getInputId($model,'groupsToBind')?>'),
        "<?=Url::to(['group/get-selection-list'])?>","<?=Url::to(['group/get-selection-by-id'])?>",true,'100%');

</script>

