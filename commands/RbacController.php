<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 03.03.2015
 * Time: 13:55
 */

namespace app\commands;
use app\rbac\OwnAccount;
use app\rbac\OwnBeacon;
use yii\console\Controller;
use yii\rbac\DbManager;
class RbacController extends Controller
{
    const manageBeacon = 'manageBeacon';
    const manageUserAccount = 'manageUserAccount';
    const superAdmin = 'superAdmin';
    const manageOwnBeacon = 'manageOwnBeacon';
    const manageOwnAccount = 'manageOwnAccount';
    const user = 'user';
    const MANAGE_BEACON = 'manageBeacon';
    public function actionInit()
    {
        /**@var DbManager $auth*/
        $auth = \Yii::$app->authManager;
        $auth->removeAll();
        $auth->invalidateCache();

        // add "createPost" permission
        $manageBeacon = $auth->createPermission(self::manageBeacon);
        $manageBeacon->description = 'Manage a beacon';
        $auth->add($manageBeacon);

        // add "updatePost" permission
        $manageUserAccount = $auth->createPermission(self::manageUserAccount);
        $manageUserAccount->description = 'Manage user account';
        $auth->add($manageUserAccount);


        $superAdmin = $auth->createRole('superAdmin');
        $auth->add($superAdmin);
        $auth->addChild($superAdmin, $manageBeacon);
        $auth->addChild($superAdmin,$manageUserAccount);



        $ownBeaconRule = new OwnBeacon();
        $auth->add($ownBeaconRule);

        $manageOwnBeacon = $auth->createPermission(self::manageOwnBeacon);
        $manageOwnBeacon->description = 'Manage own beacon';
        $manageOwnBeacon->ruleName = $ownBeaconRule->name;
        $auth->add($manageOwnBeacon);

        $auth->addChild($manageOwnBeacon,$manageBeacon);

        $ownAccountRule = new OwnAccount();
        $auth->add($ownAccountRule);

        $manageOwnAccount = $auth->createPermission(self::manageOwnAccount);
        $manageOwnAccount->description = 'Manage own account';
        $manageOwnAccount->ruleName = $ownAccountRule->name;
        $auth->add($manageOwnAccount);

        $auth->addChild($manageOwnAccount,$manageUserAccount);

        $user = $auth->createRole(self::user);
        $auth->add($user);

        $auth->addChild($user,$manageOwnBeacon);
        $auth->addChild($user,$manageOwnAccount);

        // add "author" role and give this role
    }

    public static $role_hierarchy = [
        RbacController::user
    ];
}