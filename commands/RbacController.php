<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 12:44
 */

namespace app\commands;

use app\rbac\CanDelete;
use app\rbac\CanEdit;
use app\rbac\CanEditBeacon;
use app\rbac\UserGroupRule;
use Yii;
use yii\console\Controller;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    const super_admin = 'super_admin';
    const admin = 'admin';
    const user = 'user';
    const create_beacon = 'create_beacon';
    const update_beacon = 'update_beacon';
    const user_update_beacon = 'user_update_beacon';
    const delete_beacon = 'delete_beacon';

    const create_profile = 'create_profile';
    const update_profile = 'update_profile';
    const delete_profile = 'delete_profile';


    public function actionInit()
    {
        /**@var DbManager $auth*/
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        $auth->invalidateCache();

        $create_profile = $auth->createPermission(self::create_profile);
        $auth->add($create_profile);

        $can_edit = new CanEdit();
        $auth->add($can_edit);

        $update_profile = $auth->createPermission(self::update_profile);
        $update_profile->ruleName = $can_edit->name;
        $auth->add($update_profile);

        $can_delete = new CanDelete();
        $auth->add($can_delete);
        $delete_profile = $auth->createPermission(self::delete_profile);
        $delete_profile->ruleName = $can_delete->name;
        $auth->add($delete_profile);

        $create_beacon = $auth->createPermission(self::create_beacon);
        $auth->add($create_beacon);

        $delete_beacon = $auth->createPermission(self::delete_beacon);
        $auth->add($delete_beacon);

        $can_edit_beacon = new CanEditBeacon();
        $auth->add($can_edit_beacon);

        $update_beacon = $auth->createPermission(self::update_beacon);
        $auth->add($update_beacon);

        $user_update_beacon = $auth->createPermission(self::user_update_beacon);
        $user_update_beacon->ruleName = $can_edit_beacon->name;
        $auth->add($user_update_beacon);
        $auth->addChild($user_update_beacon,$update_beacon);


        $user_group_rule = new UserGroupRule();
        $auth->add($user_group_rule);
        $user = $auth->createRole(self::user);
        $user->ruleName = $user_group_rule->name;
        $auth->add($user);
        $auth->addChild($user,$user_update_beacon);
        $auth->addChild($user,$update_profile);

        $admin = $auth->createRole(self::admin);
        $admin->ruleName = $user_group_rule->name;
        $auth->add($admin);
        $auth->addChild($admin,$user);
        $auth->addChild($admin,$update_beacon);
        $auth->addChild($admin,$delete_beacon);
        $auth->addChild($admin,$delete_profile);
        $auth->addChild($admin,$create_beacon);
        $auth->addChild($admin,$create_profile);

        $superAdmin = $auth->createRole(self::super_admin);
        $superAdmin->ruleName = $user_group_rule->name;
        $auth->add($superAdmin);
        $auth->addChild($superAdmin,$admin);

    }

    public static $role_hierarchy = [
        self::user => self::admin,
        self::admin => self::super_admin,
        self::super_admin => null
    ];
}