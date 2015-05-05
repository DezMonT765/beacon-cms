<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordChangeForm */

$this->title = 'Consilium Partnership - Password Change';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container marged">
<div class="site-reset-password">
    <h1>CHANGE PASSWORD</h1>

    <p>Please choose your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app','Password')) ?>
            <?= $form->field($model, 'passwordConfirm')->passwordInput()->label(Yii::t('app','Confirm password')) ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
