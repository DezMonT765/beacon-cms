<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;


use yii\helpers\Url;

class UserBeaconLayout extends TabbedLayout
{

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[SiteLayout::beacons()]));
    }

    public static function getTabs($active = [])
    {

        $tabs =  [
            ['label'=>'List','url'=>Url::to(['beacon/list']),'active'=>self::getActive($active,TabbedLayout::listing())],
        ];

        if(self::getActive($active,TabbedLayout::update()))
            $tabs[] =
                ['label'=>'Update','url'=>Url::to(['beacon/update'] + $_GET),'active'=>self::getActive($active,TabbedLayout::update())];

        if(self::getActive($active,TabbedLayout::view()))
            $tabs[] =
                ['label'=>'View','url'=>Url::to(['beacon/view'] + $_GET),'active'=>self::getActive($active,TabbedLayout::view())];

        return $tabs;
    }

}