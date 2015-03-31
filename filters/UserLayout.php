<?php


namespace app\filters;


use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */
class UserLayout extends TabbedLayout
{

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[self::users()]));
    }

    public static function getTabs($active = [])
    {
            $tabs = [
                ['label'=>'View','url'=>Url::to(['user/view'] + $_GET),'active'=>self::getActive($active,self::view())],
                ['label'=>'Update','url'=>Url::to(['user/update'] + $_GET),'active'=>self::getActive($active,self::update())],
            ];

        return $tabs;
    }

}