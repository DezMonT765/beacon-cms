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
 * @method static content_elements()
 * @method static content_element_create()
 * @method static beacon_create_button()
 * @method static content_element_create_button()
 */
class BeaconContentElementsLayout extends BeaconManageLayout
{

    public $layout = 'subTabbedLayout';

    public static function layout($active = []) {
        return parent::layout(ArrayHelper::merge($active,[BeaconManageLayout::content_elements()]));

    }


    public static function getActiveMap() {
        return ArrayHelper::merge(parent::getActiveMap(), [
            'list' => [BeaconManageLayout::content_element_create_button(),BeaconContentElementsLayout::listing()],
            'create' => [BeaconContentElementsLayout::create()],
            'update' => [BeaconContentElementsLayout::update()],
        ]);
    }




    public static function getTopControlButtons($active) {
        $buttons = [];

            $buttons[] = ['label' => Yii::t('beacon_layout', ':beacon_content_create'),
                          'url' => Url::to(['beacon-content-element/create'] + $_GET),
                          'active' => self::getActive($active, BeaconManageLayout::content_element_create_button()),
                          'options' => ['class' => 'btn btn-success']];

        return $buttons;
    }

    public static function getTopSubTabs(array $active = []) {
        $tabs =  [];
            $tabs[] =
                ['label' => Yii::t('beacon_layout', ':beacon_content_elements'),
                 'url' => Url::to(['beacon-content-element/list'] + $_GET),
                 'active' => self::getActive($active, BeaconManageLayout::listing())];
            if(self::getActive($active,BeaconManageLayout::update())) {
                $tabs[] =
                    ['label' => Yii::t('beacon_layout', ':beacon_content_elements_update'),
                     'url' => Url::to(['beacon-content-element/update'] + $_GET),
                     'active' => self::getActive($active, BeaconManageLayout::update())];
            }
            if(self::getActive($active,BeaconManageLayout::create())) {
                $tabs[] =
                    ['label' => Yii::t('beacon_layout', ':beacon_content_elements_create'),
                     'url' => Url::to(['beacon-content-element/create'] + $_GET),
                     'active' => self::getActive($active, BeaconManageLayout::create())];
            }
        return $tabs;
    }






}