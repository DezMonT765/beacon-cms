<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;


use yii\helpers\Url;

class UserLayout extends SiteLayout
{
    public $layout = 'tabbedLayout';
    const user_list = 'user_list';
    const user_create = 'user_create';
    const user_view = 'user_view';
    const user_update = 'user_update';
    public static function layout($active = [])
    {
        $active = array_merge($active,[SiteLayout::users => true,SiteLayout::profile => true]);
        $nav_bar = parent::layout($active);
        $nav_bar['tabs'] = static::getUserTabs($active);
        return $nav_bar;
    }

    public static function getUserTabs($active = [])
    {
            $tabs = [
                ['label'=>'View','url'=>Url::to(['user/view'] + $_GET),'active'=>self::getActive($active,self::user_view)],
                ['label'=>'Update','url'=>Url::to(['user/update'] + $_GET),'active'=>self::getActive($active,self::user_update)],
            ];

        return $tabs;
    }

}