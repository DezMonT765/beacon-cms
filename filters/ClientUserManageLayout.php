<?php
namespace app\filters;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 * @method static import()
 * @method static info()
 */

class ClientUserManageLayout extends ClientUserLayout
{
    public $layout = 'subTabbedLayout';

    public static  function getActiveMap()
    {
        $active_map =  array_merge(parent::getActiveMap(),[
            'beacons' => [ClientUserManageLayout::beacons()],
            'update' => [ClientUserManageLayout::update()],
            'info' => [ClientUserManageLayout::info()],
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
            ['label'=>Yii::t('client_layout',':user_update'),'url'=>Url::to(['client-user/update'] +  self::getParams()),'active'=>self::getActive($active,ClientUserManageLayout::update())],
            ['label'=>Yii::t('client_layout',':user_info'),'url'=>Url::to(['client-user/info'] + $_GET),'active'=>self::getActive($active,ClientUserManageLayout::info())],
            ['label'=>Yii::t('client_layout',':user_beacons'),'url'=>Url::to(['client-user/beacons'] +  self::getParams()),'active'=>self::getActive($active,ClientUserManageLayout::beacons())],
        ];
        return $tabs;
    }
}