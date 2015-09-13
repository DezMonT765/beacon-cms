<?php
namespace app\filters;
use app\models\ClientUsers;
use Yii;
use yii\base\ActionFilter;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.09.2015
 * Time: 15:07
 */

class AuthKeyFilter extends ActionFilter {
   public function beforeAction($action) {
       $auth_key = Yii::$app->request->getQueryParam('auth_key');
       $client_user = null;
       if($auth_key !== null)
            $client_user = ClientUsers::findOne(['auth_key'=>$auth_key]);
       if(!($client_user instanceof ClientUsers)) {
           throw new HttpException(403,'You are not allowed to perform this action');
       }
       else {
           $action->controller->client_user = $client_user;
           return true;
       }
   }
}