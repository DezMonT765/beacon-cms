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
        <?php $form = ActiveForm::begin([
                                            'options' => ['enctype' => 'multipart/form-data']
                                        ]); ?>

        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'alias')->textInput() ?>
        <?= $form->field($model, 'description')->textarea() ?>
        <?if($model->map) : ?>
        <img width="200" src="<?=$model->getFile('map')?>">
        <?endif?>
        <?= $form->field($model, 'map')->fileInput() ?>
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
<script>
    $('#<?=Html::getInputId($model,'name')?>').on('change',function() {
        var value = $(this).prop('value');
        $.ajax({
            url : "<?=Url::to(['group/get-alias'])?>",
            type : "GET",
            dataType : "json",
            data : {
                value : value
            },
            success : function (data) {
                if(data.success) {
                    $('#<?=Html::getInputId($model,'alias')?>').prop('value',data['alias']);
                }
            }
        });
    });

</script>
