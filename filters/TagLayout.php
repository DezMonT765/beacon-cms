<?php
namespace app\filters;

use app\commands\RbacController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 * @method static tag_create_button()
 * @method static map()
 */
class TagLayout extends TabbedLayout
{



    public static function getActiveMap() {
        return ArrayHelper::merge(parent::getActiveMap(), [
            'list' => [TagLayout::tag_create_button()],
        ]);
    }


    public static function layout(array $active = []) {
        return parent::layout(array_merge($active, [SiteLayout::tags()]));
    }


    public static function getTopControlButtons($active) {
        $buttons = [];
        if(Yii::$app->user->can(RbacController::admin)) {
            $buttons[] =
                ['label' => Yii::t('app', ':tag_create'), 'url' => Url::to(['tag/create'] + $_GET),
                 'active' => self::getActive($active, TagLayout::tag_create_button()),
                 'options' => ['class' => 'btn btn-success']];
        }
        return $buttons;
    }


    public static function getTabs(array $active = []) {
        $tabs = [
            ['label' => Yii::t('app', ':tag_list'), 'url' => Url::to(['tag/list']),
             'active' => self::getActive($active, TabbedLayout::listing())],
        ];

        if(self::getActive($active, TabbedLayout::update())) {
            $tabs[] =
                ['label' => Yii::t('app', ':tag_update'), 'url' => Url::to(['tag/update'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::update())];
        }
        if(self::getActive($active, TabbedLayout::create())) {
            $tabs[] =
                ['label' => Yii::t('app', ':tag_create'), 'url' => Url::to(['tag/create'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::create())];
        }
        if(self::getActive($active, TabbedLayout::view())) {
            $tabs[] =
                ['label' => Yii::t('app', ':tag_view'), 'url' => Url::to(['tag/view'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::view())];
        }
        return $tabs;
    }

}