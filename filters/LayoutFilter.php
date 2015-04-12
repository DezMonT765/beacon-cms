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

    public static $role = null;

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
        if(self::$role === null)
        {
            if(\Yii::$app->user->isGuest)
            {
                self::$role = "Guest";
            }
            else
            {
                switch (\Yii::$app->user->identity->role)
                {
                    case RbacController::super_admin:
                        self::$role = RbacController::super_admin;
                        break;
                    case RbacController::admin:
                        self::$role = RbacController::admin;
                        break;
                    case RbacController::user:
                        self::$role = RbacController::user;
                        break;
                }
            }
        }
        return self::$role;
    }

    public static  function getActive($active,$tabs)
    {
        $active = array_flip($active);
        if(is_array($tabs))
        {
            $result = false;
            foreach ($tabs as $tab)
            {
                $result |= isset($active[$tab]);
            }
            return $result;
        }
        else
        return (isset($active[$tabs]) ? true : false);
    }

    public static function __callStatic($name,$attributes)
    {
        return get_called_class().$name;
    }

}