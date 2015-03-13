
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
                                            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                            'labelOptions' => ['class' => 'col-lg-1 control-label'],
                                        ],
                                    ]); ?>
    <?= $form->errorSummary($model);?>
    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
    <?= $form->field($model, 'group_token') ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
