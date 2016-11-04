<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 29.04.2015
 * Time: 10:38
 * @var $this \yii\web\View
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

\app\assets\Select2Asset::register($this);
\app\assets\BeaconMapAsset::register($this);
$file_models = $map_provider->getModels();
$file_name = null;
$file_id = null;
foreach($file_models as $id => $name) :
    $file_name = $name;
    $file_id = $id;

endforeach
?>

<br>

<div class="row-fluid">

    <div class="col-md-12">
        <div class="row">
        <? $form = ActiveForm::begin(['options' => ['class'=>'col-md-6'],

                                      'method' => "GET"
                                     ]); ?>
        <div class="form-group">
        <?= \yii\helpers\Html::textInput('group_id', $group->id,['id'=>'group-list']); ?>
        </div>
        <?=\yii\helpers\Html::submitButton('Select group',['class'=>'btn btn-success']);?>
        <? ActiveForm::end(); ?>
        </div>
    </div>
    <?if($file_name) : ?>
    <div class="col-md-12" id="root"></div>
        <script>
            var beaconMap = new lib.BeaconMap('root',
                "<?=$group->getFileByName($file_name,'map') ?>",
                8,8);
            beaconMap.init();
            initSelect($('#beacon-pin'),
                "<?=Url::to(['not-pinned-beacon/get-selection-list','group_id'=>$group->id])?>",
                "<?=Url::to(['not-pinned-beacon/get-selection-by-id'])?>", false, '100%');
        </script>
    <div class="col-md-12">
        <?= LinkPager::widget([
                                      'pagination' => $map_provider->pagination,
                                  ]);
        ?>
    </div>
    <?else : ?>
        <div class="col-md-12">
        <div class="alert alert-danger" role="alert">There are no map in selected group!</div>
        </div>
    <?endif?>

</div>
<script>
    initSelect($('#group-list'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%');

</script>

