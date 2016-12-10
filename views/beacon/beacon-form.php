<?php
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Beacons */
/* @var $form yii\widgets\ActiveForm */
\app\assets\Select2Asset::register($this);
\app\assets\CropAsset::register($this);
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
        <?php if(Yii::$app->user->can(\app\commands\RbacController::admin)): ?>
            <fieldset class="col-md-6">
                <legend><?php echo Yii::t('beacon', ':system') ?></legend>
                <?= $form->field($model, 'name')->textInput() ?>
                <? if(!(isset($group) && $group instanceof \app\models\Groups)) : ?>
                    <?= $form->field($model, 'groupToBind')->textInput(['class' => '']) ?>
                <? endif ?>

                <?= $form->field($model, 'tagsToBind')->textInput(['class'=>'']) ?>
                <script>
                    initSelect($('#<?= Html::getInputId($model,'tagsToBind')?>'),
                        "<?=Url::to(['tag/get-selection-list'])?>","<?=Url::to(['tag/get-selection-by-id'])?>",true,'100%');

                </script>

                <?= $form->field($model, 'uuid')->textInput(['maxlength' => 50]) ?>

                <?= $form->field($model, 'major')->textInput() ?>
                <?= $form->field($model, 'minor')->textInput() ?>
                <?= $form->field($model, 'place')->textInput(['maxlength' => 256]) ?>
            </fieldset>
        <? endif ?>
        <fieldset class="col-md-6" style="border-left:1px solid #afafaf;">
            <legend><?php echo Yii::t('beacon', ':content') ?></legend>
            <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'link')->textInput(['maxlength' => 512]) ?>

            <?= $form->field($model, 'description')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'en',
                    'minHeight' => '200px',
                    'maxHeight' => '400px',
                    'maxWidth' => '300px',
                    'imageUpload' => Url::to(['beacon/save-redactor-image']),
                    'imageUploadParam' => 'Beacons[picture]'
                ]
            ]); ?>
            <?= $form->field($model, 'additional_info')->textArea() ?>

            <?= $form->field($model, 'picture')->fileInput(['maxlength' => 256]) ?>
            <?= $form->field($model, 'horizontal_picture')->fileInput(['maxlength' => 256]) ?>
        </fieldset>
        <fieldset class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('beacon', ':create') : Yii::t('beacon', ':update'),
                                       ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </fieldset>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    function getBeaconData(id) {
        $.ajax({
            url: "<?=Url::to(['group/as-ajax'])?>",
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $('#<?= Html::getInputId($model, 'uuid')?>').prop('value', data.uuid);
                $('#<?= Html::getInputId($model, 'major')?>').prop('value', data.major);
                $('#<?= Html::getInputId($model, 'minor')?>').prop('value', data.minor);
                $('#<?= Html::getInputId($model, 'place')?>').prop('value', data.place);
            }
        });
    }
    $(document).ready(function() {
        <?if($model->isNewRecord && isset($group) && $group instanceof \app\models\Groups) :?>
        var value = <?=$group->id?>;
            getBeaconData(value);
        <?endif?>
    });

    initSelect($('#<?= Html::getInputId($model, 'groupToBind')?>'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%').on('change', function (e) {
        <?if($model->isNewRecord) : ?>
        getBeaconData(e.val);
        <?endif?>
    });

    initSelect($('#<?= Html::getInputId($model, 'groupToBind')?>'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%').on('change', function (e) {
        <?if($model->isNewRecord) : ?>
        getBeaconData(e.val);
        <?endif?>
    });

    var image_id = "<?=Html::getInputId($model, 'picture')?>";
    var image_crop = new Crop('picture', 250, 3 / 4, image_id);
    $('#' + image_id).on('click', function () {
        $(this).attr('value', null);
    }).on('change', function (e) {
        image_crop.start(e);
    });

    var h_image_id = "<?=Html::getInputId($model, 'horizontal_picture')?>";
    var h_image_crop = new Crop('horizontal_picture', 250, 4 / 3, h_image_id);
    $('#' + h_image_id).on('click', function () {
        $(this).attr('value', null);
    }).on('change', function (e) {
        h_image_crop.start(e);
    });

</script>
