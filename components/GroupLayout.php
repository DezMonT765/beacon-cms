<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;


use yii\helpers\Url;

class GroupLayout extends SiteLayout
{
    public $layout = 'tabbedLayout';
    const group_list = 'group_list';
    const group_create = 'group_create';
    const group_view = 'group_view';
    const group_update = 'group_update';
    public static function layout($active = array())
    {
        $active = array_merge($active,[SiteLayout::groups => true]);
        $nav_bar = parent::layout($active);
        $nav_bar['tabs'] = static::getTabs($active);
        return $nav_bar;
    }

    public static function getTabs($active = [])
    {
        $tabs =  [
            ['label'=>'List','url'=>Url::to(['group/index']),'active'=>self::getActive($active,self::group_list)],
            ['label'=>'Create','url'=>Url::to(['group/create']),'active'=>self::getActive($active,self::group_create)]
        ];

        if(self::getActive($active,self::group_update))
            $tabs[] =
                ['label'=>'Update','url'=>Url::to(['group/update'] + $_GET),'active'=>self::getActive($active,self::group_update)];

        if(self::getActive($active,self::group_view))
            $tabs[] =
                ['label'=>'View','url'=>Url::to(['group/view'] + $_GET),'active'=>self::getActive($active,self::group_view)];

        return $tabs;
    }

}