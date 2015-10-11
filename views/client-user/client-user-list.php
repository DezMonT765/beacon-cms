<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Client Users');
?>
<div class="client-user-index">
<br>


    <?= GridView::widget([
        'id' => 'client-user-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],

            [
                'attribute'=>'email',
                'format'=>'raw',
                'value'=>function($data)
                {
                    return Html::a($data->email,Url::to(['view','id'=>$data->id]));
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}{delete}'
            ],
        ],
    ]); ?>
    <?php  echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-client-user'])?>

</div>
<script>
    $("#delete-client-user").click(function()
    {
        if(confirm("Are you sure you want to delete all selected categories?"))
        {
            var list = $('#client-user-list').yiiGridView('getSelectedRows');
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