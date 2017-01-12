<?php
namespace app\filters;

use app\models\MainActiveRecord;
use Yii;
use yii\base\ActionFilter;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.09.2015
 * Time: 15:07
 * @property string|MainActiveRecord $model_class
 */
class AuthKeyFilter extends ActionFilter
{
    public $param = 'auth_key';
    public $model_class = null;
    public $model = null;


    public function beforeAction($action) {
        $auth_key = Yii::$app->request->getQueryParam($this->param);
        $client_user = null;
        $model_class = $this->model_class;
        if($auth_key !== null) {
            $client_user = $model_class::findOne([$this->param => $auth_key]);
        }
        if($model_class !== null && !$client_user instanceof $model_class) {
            throw new HttpException(403, 'You are not allowed to perform this action');
        }
        else {
            $this->model = $client_user;
            return true;
        }
    }
}