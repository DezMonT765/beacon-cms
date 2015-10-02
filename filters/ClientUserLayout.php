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
 */

class ClientUserLayout extends SubTabbedLayout
{

    public $layout = 'tabbedLayout';

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[SiteLayout::client_users()]));
    }

    public static function getTabs($active = [])
    {
        $tabs =  [
            ['label'=>Yii::t('client_user', ':client_user'),'url'=>Url::to(['client-user/list']),'active'=>self::getActive($active,TabbedLayout::listing())],
        ];

        if(self::getActive($active,TabbedLayout::update()))
            $tabs[] =
                ['label'=>Yii::t('client_user', ':client_user'),'url'=>Url::to(['client-user/update'] + $_GET),'active'=>self::getActive($active,TabbedLayout::update())];


        return $tabs;
    }
}