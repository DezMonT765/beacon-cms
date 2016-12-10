<?php
namespace app\filters;

use app\commands\RbacController;
use app\models\Users;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 * @method static profile()
 * @method static users()
 * @method static beacons()
 * @method static login()
 * @method static register()
 * @method static groups()
 * @method static translations()
 * @method static client_users()
 * @method static tags()
 */
class SiteLayout extends LayoutFilter
{


    const place_left_nav = 'left_nav';
    const place_right_nav = 'right_nav';


    public static function getActiveMap() {
        return [
            'login' => [SiteLayout::login()],
            'register' => [SiteLayout::register()],
        ];
    }


    public static function getParams() {
        $params = isset($_GET['id']) ? ['id' => $_GET['id']] : [];
        return $params;
    }


    public static function layout(array $active = []) {
        $user_role = self::getRole();
        $nav_bar = [];
        switch($user_role) {
            case 'Guest' :
                $nav_bar = [
                    self::place_left_nav => [],
                    self::place_right_nav => self::getGuestRightNav($active),
                ];
                break;
            case RbacController::promo_user :
            case RbacController::user :
                $nav_bar = [
                    self::place_left_nav => self::getLeftTabs($active),
                    self::place_right_nav => self::getRightNav(),
                ];
                break;
            case RbacController::admin :
            case RbacController::super_admin :
                $nav_bar = [
                    self::place_left_nav => self::getAdminLeftTabs($active),
                    self::place_right_nav => self::getRightNav(),
                ];
        }
        return $nav_bar;
    }


    public static function getGuestLeftTabs($active) {
        return [
            ['label' => Yii::t('site_layout', ':login'), 'url' => Url::to(['site/login']),
             'active' => self::getActive($active, SiteLayout::login())],
            ['label' => Yii::t('site_layout', ':register'), 'url' => Url::to(['promo/register']),
             'active' => self::getActive($active, SiteLayout::register())]
        ];
    }


    public static function getLeftTabs($active) {
        $tabs = [
            ['label' => Yii::t('site_layout', ':my_beacons'), 'url' => Url::to(['beacon/list']),
             'active' => self::getActive($active, SiteLayout::beacons())]
        ];
        if(self::getActive($active, self::profile())) {
            $user = Users::getLogged(true);
            $tabs[] =
                ['label' => Yii::t('site_layout', ':my_profile'), 'url' => Url::to(['user/view', 'id' => $user->id]),
                 'active' => self::getActive($active, SiteLayout::profile())];
        }
        return $tabs;
    }


    public static function getAdminLeftTabs($active) {
        $tabs = [
            ['label' => Yii::t('site_layout', ':users'), 'url' => Url::to(['user/list']),
             'active' => self::getActive($active, SiteLayout::users())],
            ['label' => Yii::t('site_layout', ':client_user'), 'url' => Url::to(['client-user/list']),
             'active' => self::getActive($active, SiteLayout::client_users())],
            ['label' => Yii::t('site_layout', ':beacons'), 'url' => Url::to(['beacon/list']),
             'active' => self::getActive($active, SiteLayout::beacons())],
            ['label' => Yii::t('site_layout', ':groups'), 'url' => Url::to(['group/list']),
             'active' => self::getActive($active, SiteLayout::groups())],
            ['label' => Yii::t('app', ':tags'), 'url' => Url::to(['tag/list']),
             'active' => self::getActive($active, SiteLayout::tags())],
            ['label' => Yii::t('site_layout', ':translations'), 'url' => Url::to(['translation/list']),
             'active' => self::getActive($active, SiteLayout::translations())],
        ];
        return $tabs;
    }


    public static function getGuestRightNav($active) {
        return [
            ['label' => Yii::t('site_layout', ':login'), 'url' => Url::to(['site/login']),
             'active' => self::getActive($active, SiteLayout::login())],
            ['label' => Yii::t('site_layout', ':register'), 'url' => Url::to(['promo/register']),
             'active' => self::getActive($active, SiteLayout::register())]
        ];
    }


    public static function getRightNav() {
        $user = Users::getLogged(true);
        return [
            ['label' => Yii::t('site_layout', ':hello') . ' ' . $user->email, 'items' => [
                ['label' => Yii::t('site_layout', ':my_profile'), 'url' => Url::to(['user/view', 'id' => $user->id])],
                ['label' => Yii::t('site_layout', ':logout'), 'url' => ['site/logout']]
            ]],
        ];
    }


}