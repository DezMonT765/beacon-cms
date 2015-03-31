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
                                     'format'=>'html',
                                     'value'=>function($data)
                                     {
                                         return Html::a($data->email,Url::to(['update','id'=>$data->id]));
                                     }
                                 ],
                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'filter' => Users::$statuses,
                                     'attribute'=>'status',
                                     'url'=>['ajaxUpdate'],
                                     'type'=>'select',
                                     'value' => function($data){
                                         return Users::getStatus($data->status);
                                     },
                                     'editableOptions'=>[
                                         'source' => Users::$statuses
                                     ]
                                 ],
                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'filter' => Users::$roles,
                                     'attribute'=>'role',
                                     'url'=>['ajaxUpdate'],
                                     'value' => function($data){
                                         return Users::getRole($data->role);
                                     },
                                     'type'=>'select',
                                     'editableOptions'=>
                                         [
                                             'source' => Url::to('available-groups'),
                                             'sourceCache' => false
                                         ]
                                 ],



                                 ['class' => 'yii\grid\ActionColumn'],
                             ],
                         ]); ?>

</div>
<script>
    function colors(value, sourceData) {
        alert(1);
        var selected = $.grep(sourceData, function (o) {
                return value == o.value;
            }),
            colors = '<?=json_encode(Users::$status_colors)?>';
        $(this).text(selected[0].text).css("color", colors[value]);
    }
</script>
