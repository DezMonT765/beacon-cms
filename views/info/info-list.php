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

            ['class' => 'yii\grid\ActionColumn'],
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
            $.post("<?=Url::to(['mass-delete'])?>",newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>