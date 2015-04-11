<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Groups');
?>
<div class="groups-index">

   <br>

    <?= GridView::widget([
        'id' => 'group-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'name',
            'alias',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-groups'])?>
</div>
<script>
    $('#delete-groups').click(function()
    {
        if(confirm("<?=Yii::t('app','Are you sure you want to delete all selected categories?')?>"))
        {
            var list = $('#group-list').yiiGridView('getSelectedRows');
            console.log(list);
            var newlist = {};
            $(list).each(function(index,value)
            {
                newlist["keys["+index+"]"] = value;
            });
            console.log(newlist);
            $.post('<?=Url::to(['mass-delete'])?>',newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>
