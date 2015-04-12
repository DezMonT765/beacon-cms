<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;


use yii\helpers\Url;

class AdminUserManageLayout extends AdminUserLayout
{
    public $layout = 'subTabbedLayout';

    public static  function getActiveMap()
    {
        $active_map =  array_merge(parent::getActiveMap(),[
            'beacons' => [AdminUserManageLayout::beacons()],
            'update' => [AdminUserManageLayout::update()],
        ]);
        return $active_map;
    }

    public static function layout(array $active = [])
    {
        $nav_bar = parent::layout(array_merge($active,[TabbedLayout::update()]));
        return $nav_bar;
    }


    public static function getLeftSubTabs(array $active = [])
    {
        $tabs =  [

            ['label'=>'Update','url'=>Url::to(['user/update'] +  self::getParams()),'active'=>self::getActive($active,AdminUserManageLayout::update())],
            ['label'=>'View','url'=>Url::to(['user/view'] + $_GET),'active'=>self::getActive($active,TabbedLayout::view())],
            ['label'=>'Beacons','url'=>Url::to(['user/beacons'] +  self::getParams()),'active'=>self::getActive($active,AdminUserManageLayout::beacons())],
        ];
        return $tabs;
    }


}