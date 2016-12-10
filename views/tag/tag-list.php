<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tags');
?>
<div class="tag-index">
<br>


    <?= GridView::widget([
        'id' => 'tag-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],

            'id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php  echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-tag'])?>

</div>
<script>
    $("#delete-tag").click(function()
    {
        if(confirm("Are you sure you want to delete all selected categories?"))
        {
            var list = $('#tag-list').yiiGridView('getSelectedRows');
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