<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientBeaconSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Client Beacons');
?>
<div class="client-beacon-index">
<br>


    <?= GridView::widget([
                             'id' => 'client-beacon-list',
                             'dataProvider' => $dataProvider,
                             'filterModel' => $searchModel,
                             'columns' => [
                                 ['class' => 'yii\grid\CheckboxColumn'],

                                 'beaconTitle',
                                 'created',
                                 'updated',

                                 ['class' => 'yii\grid\ActionColumn',
                                  'controller' => 'client-beacon',
                                  'template' => '{delete}',
                                  'buttons' => [
                                      'delete' => function($url,$model,$key) {
                                          $url = Url::to(['client-beacon/delete','id'=>$model->id,'url'=> ['client-user/beacons','id'=>$_GET['id']]]);
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
    <?php  echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-client-beacon'])?>

</div>
<script>
    $("#delete-client-beacon").click(function()
    {
        if(confirm("Are you sure you want to delete all selected categories?"))
        {
            var list = $('#client-beacon-list').yiiGridView('getSelectedRows');
            var newlist = {};
            $(list).each(function(index,value)
            {
                newlist["keys["+index+"]"] = value;
            });
            $.post("<?=Url::to(['client-beacon/mass-delete','url'=> ['client-user/beacons','id'=>$_GET['id']]])?>",newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>