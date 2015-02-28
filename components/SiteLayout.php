<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 19:34
 */

namespace app\components;

use yii\base\ActionFilter;
use \yii\helpers\Url;
use \app\models\Users;

class SiteLayout extends ActionFilter
{

    const users = 'users';
    const beacons = 'beacons';
    const login = 'login';
    const register = 'register';

    public function beforeAction($action)
    {
        /**@var MainView $view
         */
        $view = $action->controller->getView();
        $view->setLayoutData($this->layout($action->controller->getTabsActivity()));
        return parent::beforeAction($action);
    }

    public static function layout($active = array())
    {

        $user_role = self::getRole();
        $nav_bar = [];
        switch($user_role)
        {
            case 'Guest' : $nav_bar = [
                'left_nav' =>[],
                'right_nav' => self::getGuestRightNav($active),
            ];
                break;
            case 'Authorized' : $nav_bar = [
                'left_nav' => self::getLeftTabs($active),
                'right_nav' => self::getRightNav($active),
            ];
        }

        return $nav_bar;
    }



    /**
     * @return string
     * return the role of user
     */
    public static function getRole()
    {
        if(\Yii::$app->user->isGuest)
            return "Guest";
        else
            return "Authorized";

    }

    public static function getGuestLeftTabs($active)
    {
        return [
            ['label'=>'Login','url'=>Url::to(['site/login']),'active'=>self::getActive($active,self::login)],
            ['label'=>'Register','url'=>Url::to(['site/register']),'active'=>self::getActive($active,self::register)]
        ];
    }

    public static function getLeftTabs($active)
    {


        $tabs = [
            ['label'=>'Users','url'=>Url::to(['user/index']),'active'=>self::getActive($active,self::users)],
            ['label'=>'Beacons','url'=>Url::to(['beacon/list']),'active'=>self::getActive($active,self::beacons)]
        ];
        return $tabs;
    }

    public function getActive($active,$tab)
    {
        return (isset($active[$tab]) ? $active[$tab] : false);
    }

    public static  function getGuestRightNav($active)
    {
        return [
            ['label'=>'Login','url'=>Url::to(['site/login']),'active'=>self::getActive($active,self::login)],
            ['label'=>'Register','url'=>Url::to(['site/register']),'active'=>self::getActive($active,self::register)]
        ];
    }

    public static function getRightNav()
    {
        $user = Users::getLogged(true);
        return [
            ['label'=>'Hello, '.$user->email,'items'=>[
                ['label'=>'Log out','url'=>['site/logout']]
            ]],
        ];
    }




}