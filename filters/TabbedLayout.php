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

class TabbedLayout extends SiteLayout
{
    const place_tabs = 'tabs';
    public $layout = 'tabbedLayout';

    public static function getActiveMap()
    {

        return [
            'index' => [TabbedLayout::listing()],
            'create' => [TabbedLayout::create()],
            'update' => [TabbedLayout::update()],
            'view' => [TabbedLayout::view()],
        ];
    }

    public static function layout(array $active = [])
    {
        $nav_bar = parent::layout($active);
        $nav_bar[self::place_tabs] = static::getTabs($active);
        return $nav_bar;
    }

    public static function getTabs(array $active = [])
    {
        $tabs = [

        ];
        return $tabs;
    }
}