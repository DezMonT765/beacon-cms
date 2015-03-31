<?php
namespace app\rbac;

use app\commands\RbacController;
use app\models\Users;
use Yii;
use yii\rbac\DbManager;
use yii\rbac\Item;
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
         * @var DbManager $auth
         */
        $auth = Yii::$app->authManager;
        $current_user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest) {
            $role = $current_user->role;
            return self::generateRoleCondition(RbacController::$role_hierarchy,$role);
        }
        return false;
    }

    protected  function  generateRoleCondition($roles,$checking_role,$condition = false)
    {
        if(is_array($roles))
        {
            foreach ($roles as $role=>$parents)
            {
                $condition = $condition || ($role == $checking_role);
                if(!$condition)
                    $condition = self::generateRoleCondition($parents,$checking_role,$condition);
            }
        }
        return $condition;
    }
}