<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use app\components\MainView;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CropAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/cropper-ui/jquery.Jcrop.min.css',
    ];
    public $js = [
        'libs/cropper-ui/jquery.Jcrop.min.js',
        'libs/cropper-ui/jaml.js',
        'libs/cropper-ui/Crop.js',
    ];
    public $jsOptions = [
        'position' => MainView::POS_HEAD,
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}
