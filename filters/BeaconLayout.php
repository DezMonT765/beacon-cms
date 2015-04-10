<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;


use yii\helpers\Url;

class BeaconLayout extends TabbedLayout
{

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[self::beacons()]));
    }

    public static function getTabs($active = [])
    {

        $tabs =  [
            ['label'=>'List','url'=>Url::to(['beacon/index']),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>'Create','url'=>Url::to(['beacon/create']),'active'=>self::getActive($active,TabbedLayout::create())]
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