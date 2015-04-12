<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;


use yii\helpers\Url;

class AdminBeaconLayout extends UserBeaconLayout
{



    public static function getTabs($active = [])
    {

        $tabs = parent::getTabs($active);
        array_splice($tabs,1,0,[['label'=>'Create','url'=>Url::to(['beacon/create']),'active'=>self::getActive($active,TabbedLayout::create())]]);
        return $tabs;
    }

}