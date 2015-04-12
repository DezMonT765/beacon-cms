<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\filters;


use yii\helpers\Url;

class AdminUserLayout extends SubTabbedLayout
{
    public $layout = 'tabbedLayout';



    public static function layout(array $active = [])
    {
        $nav_bar = parent::layout(array_merge($active,[SiteLayout::users()]));
        return $nav_bar;
    }

    public static function getTabs($active = [])
    {

        $tabs =  [
            ['label'=>'List','url'=>Url::to(['user/list']),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>'Create','url'=>Url::to(['user/create']),'active'=>self::getActive($active,TabbedLayout::create())],

        ];
        if(self::getActive($active,TabbedLayout::update()))
        {
            $tabs[] = ['label'=>'Manage','url'=>Url::to(['user/update']),'active'=>self::getActive($active,TabbedLayout::update())];
        }


        return $tabs;
    }




}