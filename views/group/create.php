<?php



/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = Yii::t('app', 'Create Groups');

?>
<div class="groups-create">

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
