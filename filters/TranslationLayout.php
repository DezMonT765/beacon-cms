<?php
namespace app\filters;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 *
 */

class TranslationLayout extends TabbedLayout
{

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[SiteLayout::translations()]));
    }

    public static function getTabs($active = [])
    {
        $tabs = [
            ['label'=>'List of translations','url'=>Url::to(['translation/list'] + $_GET),'active'=>self::getActive($active,TabbedLayout::listing())],
        ];
        return $tabs;
    }
}