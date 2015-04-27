<?php

use app\models\Users;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
?>
<div class="user-index">
    <br>
    <?= GridView::widget([
                             'dataProvider' => $dataProvider,
                             'filterModel' => $searchModel,
                             'columns' => [
                                 ['class' => 'yii\grid\SerialColumn'],

                                 [
                                     'attribute'=>'email',
                                     'format'=>'raw',
                                     'value'=>function($data)
                                     {
                                         return Html::a($data->email,Url::to(['update','id'=>$data->id]));
                                     }
                                 ],
                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'filter' => Users::$statuses,
                                     'format'=>'raw',
                                     'attribute'=>'status',
                                     'url'=>['ajax-update'],
                                     'type'=>'select',
                                     'display' => 'colors',
                                     'value' => function($data){
                                         return Users::getStatus($data->status);
                                     },
                                     'editableOptions'=>function($model)
                                     {
                                         return [
                                             'source' => Users::$statuses,
                                             'value' => $model->status,
                                         ];
                                     }
                                 ],
                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'filter' => Users::roles(),
                                     'format'=>'raw',
                                     'attribute'=>'role',
                                     'url'=>['ajax-update'],
                                     'value' => function($data){
                                         return Users::getRole($data->role);
                                     },
                                     'type'=>'select',
                                     'editableOptions' => function($model)
                                     {
                                         return [
                                             'source' => Yii::$app->user->identity->getEditableRoles($model->id),
                                             'sourceCache' => false,
                                         ];
                                     }
                                 ],



                                 ['class' => 'yii\grid\ActionColumn'],
                             ],
                         ]); ?>

</div>
<script>
    function colors(value, sourceData) {
        var selected = $.grep(sourceData, function (o) {
                return value == o.value;
            }),
            colors = <?=json_encode(Users::$status_colors)?>;
        $(this).html(selected[0].text).css("color", colors[value]);
    }
</script>



