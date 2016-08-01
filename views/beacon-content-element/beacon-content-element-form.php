<?php
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\CropAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\BeaconContentElements */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="beacon-content-elements-form">
<br>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

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
    <?php if($model->picture) : ?>
        <img src="<?=$model->getFile('picture')?>" width="150" class="thumbnail" alt="">
    <?endif?>
    <?= $form->field($model, 'picture')->fileInput(['maxlength' => 255]) ?>
    <?php if($model->horizontal_picture) : ?>
        <img src="<?=$model->getFile('horizontal_picture')?>" width="150" class="thumbnail" alt="">
    <?endif?>
    <?= $form->field($model, 'horizontal_picture')->fileInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'additional_info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
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