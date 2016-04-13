<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 29.04.2015
 * Time: 10:38
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

\app\assets\Select2Asset::register($this);
?>
<br>
<?if($group->map) : ?>
<div class="row">

    <div class="col-md-9">
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
        <br>
        <script src="/js/jcanvas.min.js"></script>
        <img id="img" src="<?= $group->getFile('map') ?>" style="display: none">
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

</div>
<script>
    initSelect($('#beacon-pin'),
        "<?=Url::to(['not-pinned-beacon/get-selection-list','group_id'=>$group->id])?>", "<?=Url::to(['not-pinned-beacon/get-selection-by-id'])?>", false, '100%');
    initSelect($('#group-list'),
        "<?=Url::to(['group/get-selection-list'])?>", "<?=Url::to(['group/get-selection-by-id'])?>", false, '100%');

    function convertImageToCanvas() {
        var img = document.getElementById("img");
        var canvas = document.getElementById('canvas');
        var newwidth = 800;
        canvas.width = newwidth;
        canvas.height = img.height * (newwidth / img.width);
//        canvas.width = img.width;
//        canvas.height = img.height;
        $('#canvas').drawImage({
            layer: true,
            source: "<?=$group->getFile('map')?>",
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
        $('#canvas').drawImage({
            layer: true,
            data: data,
            source: '/img/blue pin.svg',
            x: x, y: y,
            width: $('#canvas').width() * 0.2,
            height: $('#canvas').height() * 0.2,
            fromCenter: false,
            draggable: true,
            dragstop: safePin,
            click: showPinForm,
            dblclick: gotoBeacon
        });
    }

    function gotoBeacon(layer) {
        window.location.href = layer.data.url;
    }

    function drawPins() {
        $.ajax({
            url: "<?=Url::to(['beacon-pin/json','group_id'=>$group->id])?>",
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


    function drawSprite(data) {
        $('#canvas').drawImage({
            layer: true,
            data: data,
            source: '/img/blue pin.svg',
            x: 0, y: 0,
            width: $('#canvas').width() * 0.2,
            height: $('#canvas').height() * 0.2,
            fromCenter: false,
            draggable: true,
            dragstop: safePin,
            add: safePin,
            click: showPinForm,
            dblclick: gotoBeacon
        });
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
            drawSprite({id: id, name: text, url: url});
        }

    });

    $('#manage-form').on('beforeSubmit', function () {
        var form = $($(this)).serialize();
        $.ajax({
            url: "<?=Url::to(['beacon-pin/save'])?>",
            type: "POST",
            dataType: "json",
            data: form
        });
        return false;
    });

    $('#canvas').on('click', function () {
        $('#manage-form').hide();
    })
</script>
<?else : ?>
    <div class="alert alert-danger" role="alert">There are no map in selected group!</div>
<?endif?>
