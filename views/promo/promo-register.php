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
        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
        <?= $form->field($model, 'beacon_count')->textInput(['type'=>'number']) ?>
        <?= $form->field($model,'terms_agree')->checkbox()->label('I agree to <a target="_blank" href="'.Url::to(['site/terms']).'">terms and conditions</a>');?>

    </div>

    <div class="form-group">
        <div class="col-lg-12">
            <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
