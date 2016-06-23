<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 29.04.2015
 * Time: 10:38
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

\app\assets\Select2Asset::register($this);
$file_models = $map_provider->getModels();
$file_name = null;
$file_id = null;
foreach($file_models as $id => $name) :
    $file_name = $name;
    $file_id = $id;

endforeach
?>

<br>

<div class="row">

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
    <div class="col-md-9">
        <br>
        <script src="/js/jcanvas.min.js"></script>

        <img id="img" src="<?= $group->getFileByName($file_name,'map') ?>" style="display: none">
        <canvas id="canvas"></canvas>
    </div>
    <div class="col-md-3">
        <div class="row">
            <legend>Add pin</legend>
            <div class="form-group">
                <input id="beacon-pin" type="text" value=""/>
            </div>
            <div class="form-group">
                <button class="btn btn-default" id="add-pin">Add pin</button>
            </div>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'manage-form', 'options' =>
            ['style' => 'display:none', 'class' => 'row']
                                        ]); ?>
        <fieldset>
            <legend>Manage beacon pin</legend>
        </fieldset>
        <div style="display: none">
            <?= $form->field($model, 'id')->hiddenInput() ?>
        </div>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'x')->textInput() ?>
        <?= $form->field($model, 'y')->textInput() ?>
        <?= $form->field($model, 'canvas_width')->textInput() ?>
        <?= $form->field($model, 'canvas_height')->textInput() ?>

        <button class="btn btn-default" type="button" id="remove-pin">Remove pin</button>
        <?php ActiveForm::end(); ?>
    </div>
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
    initSelect($('#beacon-pin'),
        "<?=Url::to(['not-pinned-beacon/get-selection-list','group_id'=>$group->id])?>", "<?=Url::to(['not-pinned-beacon/get-selection-by-id'])?>", false, '100%');
    initSelect($('#group-list'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%');

    $('#img').on('load',function() {
        function convertImageToCanvas() {
            var img = document.getElementById("img");
            var canvas = document.getElementById('canvas');
            var newwidth = 800;
            canvas.width = newwidth;
            canvas.height = img.height * (newwidth / img.width);
            $('#canvas').drawImage({
                layer: true,
                source: "<?=$group->getFileByName($file_name,'map')?>",
                x: 0, y: 0,
                fromCenter: false,
                width: $('#canvas').width(),
                height: $('#canvas').height()
            });
        }

        convertImageToCanvas();
        drawPins();

        function drawPin() {
            var newidth = $('#canvas').width();
            var multiplier = newidth / parseInt(this.canvas_width);
            var x = Math.round(this.x * multiplier);
            var y = Math.round(this.y * multiplier);
            var data = {id: this.id, name: this.name, url: this.url};


            drawSprite(data,newidth,x,y);

        }

        function gotoBeacon(layer) {
            window.location.href = layer.data.url;
        }

        function drawPins() {
            $.ajax({
                url: "<?=Url::to(['beacon-pin/json','group_id'=>$group->id,'group_file_id'=>$file_id])?>",
                dataType: "json",
                cache: false,
                success: function (data) {
                    $(data.pins).each(drawPin);
                }
            })
        }

        function safePin(layer) {
            var canvas = $('#canvas');
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'id')?>').prop('value', layer.data.id);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'name')?>').prop('value', layer.data.name);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'x')?>').prop('value', layer.x);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'y')?>').prop('value', layer.y);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'canvas_width')?>').prop('value', canvas.width());
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'canvas_height')?>').prop('value', canvas.height());
            $('#manage-form').trigger('submit');
        }


        function drawSprite(data,newidth,x,y) {
            var pin_width = null;
            var pin_height = null;
            var img = new Image();
            img.onload = function() {
                pin_width = this.width;
                pin_height = this.height;
                var new_pin_width = newidth * 0.2;

                var new_pin_height = new_pin_width * pin_height / pin_width;

                $('#canvas').drawImage({
                    layer: true,
                    data: data,
                    source: '/img/blue pin.svg',
                    x: x, y: y,
                    width: new_pin_width,
                    height: new_pin_height,
                    fromCenter: false,
                    draggable: true,
                    dragstop: safePin,
                    click: showPinForm,
                    dblclick: gotoBeacon
                });
            };
            img.src = '/img/blue pin.svg';
        }

        function showPinForm(layer) {
            var canvas = $('#canvas');

            $('#<?php echo \yii\helpers\Html::getInputId($model, 'id')?>').prop('value', layer.data.id);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'name')?>').prop('value', layer.data.name);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'x')?>').prop('value', layer.x);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'y')?>').prop('value', layer.y);
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'canvas_width')?>').prop('value', canvas.width());
            $('#<?php echo \yii\helpers\Html::getInputId($model, 'canvas_height')?>').prop('value', canvas.height());
            $('#manage-form').show()
                .children('#remove-pin')
                .off('click')
                .on('click', function () {
                    $('#manage-form').hide();
                    $.ajax({
                        url: "<?=Url::to(['beacon-pin/delete'])?>",
                        dataType: "json",
                        data: {
                            id: layer.data.id
                        },
                        type: "post",
                        success: function (data) {
                            if (data.success) {
                                canvas.removeLayer(layer);
                                canvas.drawLayers();
                            }
                        }
                    });

                })
                .siblings('#save-pin')
                .off('click');
        }


        $('#add-pin').on('click', function (e) {
            var beacon = $('#beacon-pin');
            if (beacon.select2('data') !== null) {
                var id = beacon.select2('data').id;
                var text = beacon.select2('data').text;
                var url = beacon.select2('data').url;
                beacon.attr('value', '');
                beacon.select2('val', '');
                drawSprite({id: id, name: text, url: url},$('#canvas').width(),0,0);
            }

        });

        $('#manage-form').on('beforeSubmit', function () {
            var form = $($(this)).serialize();
            $.ajax({
                url: "<?=Url::to(['beacon-pin/save','group_file_id'=>$file_id])?>",
                type: "POST",
                dataType: "json",
                data: form
            });
            return false;
        });

        $('#canvas').on('click', function () {
            $('#manage-form').hide();
        })
    });
    

</script>

