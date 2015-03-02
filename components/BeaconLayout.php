<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;


use yii\helpers\Url;

class BeaconLayout extends SiteLayout
{
    public $layout = 'tabbedLayout';
    const beacon_list = 'beacon_list';
    const beacon_create = 'beacon_create';
    const beacon_view = 'beacon_view';
    const beacon_update = 'beacon_update';

    public static function layout($active = array())
    {
        $active = array_merge($active,[SiteLayout::beacons => true]);
        $nav_bar = parent::layout($active);
        $nav_bar['tabs'] = self::getUserTabs($active);
        return $nav_bar;
    }

    public static function getUserTabs($active = [])
    {

        $tabs =  [
            ['label'=>'List','url'=>Url::to(['beacon/index']),'active'=>self::getActive($active,self::beacon_list)],
            ['label'=>'Create','url'=>Url::to(['beacon/create']),'active'=>self::getActive($active,self::beacon_create)]
        ];

        if(self::getActive($active,self::beacon_update))
            $tabs[] =
                ['label'=>'Update','url'=>Url::to(['beacon/update'] + $_GET),'active'=>self::getActive($active,self::beacon_update)];

        if(self::getActive($active,self::beacon_view))
            $tabs[] =
                ['label'=>'View','url'=>Url::to(['beacon/view'] + $_GET),'active'=>self::getActive($active,self::beacon_view)];

        return $tabs;
    }

}