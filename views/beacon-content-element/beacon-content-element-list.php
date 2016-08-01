<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Beacon Content Elements');
?>
<div class="beacon-content-element-index">
    <br>


    <?= GridView::widget([
                             'id' => 'beacon-content-element-list',
                             'dataProvider' => $dataProvider,
                             'filterModel' => $searchModel,
                             'columns' => [
                                 ['class' => 'yii\grid\CheckboxColumn'],
                                 [
                                     'attribute' => 'title',
                                     'format' => 'raw',
                                     'value' => function ($data) {
                                         return Html::a($data->title,
                                                        Url::to(['update', 'content_id' => $data->id] + $_GET));
                                     }
                                 ],
                                 'link',
                                 'description:html',
                                 [
                                     'class' => 'yii\grid\ActionColumn',
                                     'template' => '{update}{delete}',
                                     'buttons' => [
                                         'update' => function ($url, $model, $key) {
                                             $url = Url::to(['update', 'content_id' => $model->id] + $_GET);
                                             return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                 'title' => Yii::t('yii', 'Update'),
                                             ]);
                                         }
                                     ],
                                 ]
                             ],
                         ]); ?>
    <?php echo Html::button('Delete', ['class' => 'btn btn-danger', 'id' => 'delete-beacon-content-element']) ?>

</div>
<script>
    $("#delete-beacon-content-element").click(function () {
        if (confirm("Are you sure you want to delete all selected categories?")) {
            var list = $('#beacon-content-element-list').yiiGridView('getSelectedRows');
            var newlist = {};
            $(list).each(function (index, value) {
                newlist["keys[" + index + "]"] = value;
            });
            $.post("<?=Url::to(['mass-delete'])?>", newlist, function (data) {
                window.location.reload();
            });
        }
    });
</script>