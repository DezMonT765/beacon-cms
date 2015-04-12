<?php
namespace app\filters;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 *
 * @method static listing()
 * @method static update()
 * @method static create()
 * @method static view()
 */

class SubTabbedLayout extends TabbedLayout
{
    const place_left_sub_tabs = 'left_sub_tabs';
    public $layout = 'subTabbedLayout';



    public static function layout(array $active = [])
    {
        $nav_bar = parent::layout(array_merge($active));
        $nav_bar[self::place_left_sub_tabs] = static::getLeftSubTabs($active);
        return $nav_bar;
    }

    public static function getLeftSubTabs(array $active = [])
    {
        $tabs = [

        ];
        return $tabs;
    }


}