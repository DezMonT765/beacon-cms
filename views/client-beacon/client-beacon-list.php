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

            'id',
            'client_id',
            'beacon_id',

            ['class' => 'yii\grid\ActionColumn'],
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
            $.post("<?=Url::to(['mass-delete'])?>",newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>