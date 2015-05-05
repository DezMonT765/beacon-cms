<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;

use Yii;
use yii\helpers\Url;
/** @method static map()*/

class AdminBeaconLayout extends UserBeaconLayout
{

    public static function getActiveMap()
    {
        return array_merge(parent::getActiveMap(),[
           'map' => [AdminBeaconLayout::map()]
        ]);
    }


    public static function getTabs($active = [])
    {

        $tabs = parent::getTabs($active);
        array_splice($tabs,1,0,[['label'=>Yii::t('beacon_layout', ':beacon_create'),'url'=>Url::to(['beacon/create']),'active'=>self::getActive($active,TabbedLayout::create())]]);
        array_splice($tabs,2,0,[['label'=>Yii::t('beacon_layout', ':beacon_map'),'url'=>Url::to(['beacon/map']),'active'=>self::getActive($active,AdminBeaconLayout::map())]]);
        return $tabs;
    }

}