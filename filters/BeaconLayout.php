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

 * @method static beacon_create_button()
 * @method static map()
 */
class BeaconLayout extends SubTabbedLayout
{

    public $layout = 'tabbedLayout';


    public static function getActiveMap() {
        return ArrayHelper::merge(parent::getActiveMap(), [
            'map' => [BeaconLayout::map()],
            'list' => [BeaconLayout::beacon_create_button()],
        ]);
    }


    public static function layout($active = []) {
        return parent::layout(array_merge($active, [SiteLayout::beacons()]));
    }


    public static function getTopControlButtons($active) {
        $buttons = [];
        if(Yii::$app->user->can(RbacController::admin)) {
            $buttons[] =
                ['label' => Yii::t('beacon_layout', ':beacon_create'), 'url' => Url::to(['beacon/create'] + $_GET),
                 'active' => self::getActive($active, BeaconLayout::beacon_create_button()),
                 'options' => ['class' => 'btn btn-success']];
        }
        return $buttons;
    }


    public static function getTabs($active = []) {
        $tabs = [
            ['label' => Yii::t('beacon_layout', ':beacons_list'), 'url' => Url::to(['beacon/list']),
             'active' => self::getActive($active, TabbedLayout::listing())],
        ];
        if(Yii::$app->user->can(RbacController::admin)) {
            $tabs[] = ['label' => Yii::t('beacon_layout', ':beacon_map'), 'url' => Url::to(['beacon/map']),
                       'active' => self::getActive($active, BeaconLayout::map())];
        }
        if(self::getActive($active, TabbedLayout::update())) {
            $tabs[] =
                ['label' => Yii::t('beacon_layout', ':beacon_update'), 'url' => Url::to(['beacon/update'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::update())];
        }
        if(self::getActive($active, TabbedLayout::create())) {
            $tabs[] =
                ['label' => Yii::t('beacon_layout', ':beacon_create'), 'url' => Url::to(['beacon/create'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::create())];
        }
        if(self::getActive($active, TabbedLayout::view())) {
            $tabs[] =
                ['label' => Yii::t('beacon_layout', ':beacon_view'), 'url' => Url::to(['beacon/view'] + $_GET),
                 'active' => self::getActive($active, TabbedLayout::view())];
        }
        return $tabs;
    }

}