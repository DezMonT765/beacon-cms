<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 14:00
 */

namespace app\filters;

use app\commands\RbacController;
use app\components\MainView;
use app\controllers\MainController;
use yii\base\ActionFilter;

class LayoutFilter extends ActionFilter
{
    public $layout = 'main';


    public static  function getActiveMap()
    {
        return [];
    }

    public static function getParams()
    {
        $params = isset($_GET['id']) ? ['id'=>$_GET['id']] : [];
        return $params;
    }

    public function beforeAction($action)
    {
        /**@var MainView $view
         * @var MainController $controller
         */
        $controller = $action->controller;
        $view = $action->controller->getView();
        $controller->activeMap = array_merge(static::getActiveMap(),$controller->activeMap);
        $action->controller->layout = $this->layout;
        $view->setLayoutData($this->layout($controller->getTabsActivity()));
        return parent::beforeAction($action);
    }

    public static function layout()
    {
        return [];
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
        {
            switch(\Yii::$app->user->identity->role)
            {
                case RbacController::super_admin:
                    return RbacController::super_admin;
                    break;
                case RbacController::admin:
                    return RbacController::admin;
                    break;
                case RbacController::user:
                    return RbacController::user;
                    break;
            }
        }

    }

    public static  function getActive($active,$tab)
    {
        $active = array_flip($active);
        return (isset($active[$tab]) ? true : false);
    }

    public static function __callStatic($name,$attributes)
    {
        return get_called_class().$name;
    }

}