<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Users */
$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to register:</p>

    <?php $form = ActiveForm::begin([
                                        'id' => 'register-form',
                                        'options' => ['class' => 'form-horizontal'],
                                        'fieldConfig' => [
                                        ],
                                    ]); ?>
    <div class="col-md-6">
        <div id="step1">
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
            <div class="form-group">
                    <?= Html::button('Next', ['class' => 'btn', 'id' => 'next-btn']) ?>
            </div>
        </div>
        <div id="step2">
            <?= $form->field($model, 'beacon_count')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'group_name')->textInput() ?>
            <?= $form->field($model, 'terms_agree')->checkbox()->label('I agree to <a target="_blank" href="' . Url::to(['site/terms']) . '">terms and conditions</a>'); ?>
            <div class="form-group">
                    <?= Html::button('Previous', ['class' => 'btn', 'id' => 'previous-btn']) ?>
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>


    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $('#step2').hide();
    $('#next-btn').on('click', function () {
        $('#step1').hide();
        $('#step2').show();
    });

    $('#previous-btn').on('click', function () {
        $('#step2').hide();
        $('#step1').show();
    });
    $('#register-form').on('afterValidate',function(event,messages,errorAttributes) {
        console.log(errorAttributes);
        if(errorAttributes.length > 0) {
            $('#next-btn').hide();
            $('#step1').show();
            $('#previous-btn').hide();
        }
    });
</script>
