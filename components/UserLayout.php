<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;


class UserLayout extends SiteLayout
{

    public static function layout($active = array())
    {
        $active = array_merge($active,[SiteLayout::users => true]);
        $nav_bar = parent::layout($active);
        return $nav_bar;
    }

}