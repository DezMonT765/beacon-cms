<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 16:39
 */
namespace app\rbac;
use app\commands\RbacController;
use yii\rbac\Rule;

class AdminAccount extends Rule
{
    public $name = __CLASS__;
    /**@inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['user_role']) ? $params['user_role'] == RbacController::admin : false;
    }
}