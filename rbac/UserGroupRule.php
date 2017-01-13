<?php
namespace app\rbac;

use app\commands\RbacController;
use app\models\Users;
use Yii;
use yii\rbac\Rule;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 31.03.2015
 * Time: 12:23
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        /**@var Users $current_user
         */
        $current_user = Yii::$app->user->identity;
        if(!$current_user instanceof Users) {
            $current_user = isset($params['user']) ? $params['user'] : null;
        }
        if ($current_user instanceof Users) {
            $role = $current_user->role;
            if(isset(RbacController::getRoleHierarchy()[$item->name]) || array_key_exists($item->name,RbacController::getRoleHierarchy()))
                return RbacController::generateRoleCondition($item->name,$role);
        }
        return false;
    }
}