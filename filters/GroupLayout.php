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

class GroupLayout extends SubTabbedLayout
{
    public $layout = 'tabbedLayout';

    public static function layout($active = array())
    {
        $active = array_merge($active,[SiteLayout::groups()]);
        $nav_bar = parent::layout($active);
        return $nav_bar;
    }

    public static function getTabs($active = [])
    {
        $tabs =  [
            ['label'=>Yii::t('group_layout', ':groups_list'),'url'=>Url::to(['group/list']),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>Yii::t('group_layout', ':group_create'),'url'=>Url::to(['group/create']),'active'=>self::getActive($active,TabbedLayout::create())]
        ];

        if(self::getActive($active,TabbedLayout::update()))
            $tabs[] =
                ['label'=>Yii::t('group_layout', ':group_manage'),'url'=>Url::to(['group/update'] + $_GET),'active'=>self::getActive($active,TabbedLayout::update())];



        return $tabs;
    }

}