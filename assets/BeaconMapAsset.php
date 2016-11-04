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
class BeaconMapAsset extends AssetBundle
{
    public $basePath = '@webroot/libs/beacon-map';
    public $baseUrl = '@web/libs/beacon-map';
    public $css = [
        'style.css',
    ];
    public $js = [
        'manifest.js',
        'vendor.js',
        'style.js',
        'app.js'
    ];
    public $jsOptions = [
        'position' => MainView::POS_HEAD,
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}