<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Infos');
?>
<div class="info-index">
<br>


    <?= GridView::widget([
        'id' => 'info-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],

            'key',
            'value',

            ['class' => 'yii\grid\ActionColumn',
             'controller'=> 'info',
             'template' => '{delete}',
             'buttons' => [
                 'delete' => function($url,$model,$key) {
                     $url = Url::to(['info/delete','id'=>$model->id,'url'=> ['client-user/info','id'=>$_GET['id']]]);
                     return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                         'title' => Yii::t('yii', 'Delete'),
                         'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                         'data-method' => 'post',
                         'data-pjax' => '0',
                     ]);
                 }
             ],
            ],
        ],
    ]); ?>
    <?php  echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-info'])?>

</div>
<script>
    $("#delete-info").click(function()
    {
        if(confirm("Are you sure you want to delete all selected categories?"))
        {
            var list = $('#info-list').yiiGridView('getSelectedRows');
            var newlist = {};
            $(list).each(function(index,value)
            {
                newlist["keys["+index+"]"] = value;
            });
            $.post("<?=Url::to(['info/mass-delete','url'=>['client-user/info','id'=>$_GET['id']]])?>",newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>