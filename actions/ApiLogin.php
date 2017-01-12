<?php
namespace app\actions;
use app\helpers\Helper;
use app\models\MainActiveRecord;
use Yii;
use yii\base\Action;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 27.11.2016
 * Time: 12:20
 * @property string|MainActiveRecord $model_class
 *
 */
class ApiLogin extends Action
{
    public $model_class = null;
    public $api_key = null;
    public $login_method = null;


    public function run() {
        /**@var $model_class MainActiveRecord */
        $model_class = $this->model_class;
        $model = new $model_class();
        if($model->load(Yii::$app->request->post())) {
            if($model->{$this->login_method}()) {
                $user = $model_class::findByEmail($model->email);
                if($user instanceof $model_class) {
                    $user->group_ids = $model->group_ids;
                    $user->save();
                }
                return [$this->api_key => $user->{$this->api_key}];
            }
        }
        throw new HttpException(401,
                                'You have not been authorized ' . Helper::recursive_implode($model->errors, ',',
                                                                                            false,
                                                                                            false));
    }
}