<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetForm */

$this->title = Yii::t('password',':password_reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container marged">
<div class="site-request-password-reset">
    <h1><?=Yii::t('password',':password_reset')?></h1>

    <p><?=Yii::t('password',':enter_email_address')?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email')->label(Yii::t('app',':email')) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app',':save'),['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
