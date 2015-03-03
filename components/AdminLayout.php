<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;


use yii\helpers\Url;

class AdminLayout extends UserLayout
{


    public static function getUserTabs($active = [])
    {

        $tabs =  [
            ['label'=>'List','url'=>Url::to(['user/index']),'active'=>self::getActive($active,self::user_list)],
            ['label'=>'Create','url'=>Url::to(['user/create']),'active'=>self::getActive($active,self::user_create)]
        ];

        if(self::getActive($active,self::user_update))
            $tabs[] =
                ['label'=>'Update','url'=>Url::to(['user/update'] + $_GET),'active'=>self::getActive($active,self::user_update)];

        if(self::getActive($active,self::user_view))
            $tabs[] =
                ['label'=>'View','url'=>Url::to(['user/view'] + $_GET),'active'=>self::getActive($active,self::user_view)];

        return $tabs;
    }

}