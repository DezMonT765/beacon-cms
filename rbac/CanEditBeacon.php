<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 03.03.2015
 * Time: 14:02
 */

namespace app\rbac;

use app\models\Users;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

class CanEditBeacon extends Rule
{
    public $name = 'ownBeacon';
    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        /**@var Users $user*/
        $user = Yii::$app->user->identity;
        return (isset($params['beacon'])) ? $user->canEditBeacon($params['beacon']) : false;
    }
}