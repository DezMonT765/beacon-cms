<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Beacons */
/* @var $form yii\widgets\ActiveForm */
\app\assets\Select2Asset::register($this);
?>

<div class="beacons-form" xmlns="http://www.w3.org/1999/html">
    <br>

    <div class="row">
        <?php $form = ActiveForm::begin([
                                            'id' => 'beacon-form',
                                            'enableAjaxValidation' => false,
                                            'enableClientValidation' => true,
                                            'options' => ['enctype' => 'multipart/form-data']
                                        ]); ?>
        <?php if(Yii::$app->user->can(\app\commands\RbacController::admin)):?>
        <fieldset class="col-md-6" style="border-right:1px solid #afafaf;">
            <legend>System</legend>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'groupToBind')->textInput(['class' => '']) ?>

            <?= $form->field($model, 'uuid')->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model, 'major')->textInput() ?>
            <?= $form->field($model, 'minor')->textInput() ?>
            <?= $form->field($model, 'place')->textInput(['maxlength' => 256]) ?>
        </fieldset>
        <?endif?>
        <fieldset class="col-md-6">
            <legend>Content</legend>
            <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'picture')->fileInput(['maxlength' => 256]) ?>
        </fieldset>
        <fieldset class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        </fieldset>
    <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    initSelect($('#<?= Html::getInputId($model,'groupToBind')?>'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%').on('change',function(e){
            $.ajax({
                url : "<?=Url::to(['group/as-ajax'])?>",
                dataType : "json",
                data : {
                    id: e.val
                },
                success : function(data)
                {
                    $('#<?= Html::getInputId($model,'uuid')?>').prop('value',data.uuid);
                    $('#<?= Html::getInputId($model,'major')?>').prop('value',data.major);
                    $('#<?= Html::getInputId($model,'minor')?>').prop('value',data.minor);
                    $('#<?= Html::getInputId($model,'place')?>').prop('value',data.place);
                }
               });

            });

</script>
