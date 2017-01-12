<?php
namespace app\controllers;

use app\actions\ApiLogin;
use app\components\Alert;
use app\filters\AuthKeyFilter;
use app\filters\FilterJson;
use app\models\Beacons;
use app\models\MainActiveRecord;
use app\models\Users;
use yii\filters\VerbFilter;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 27.11.2016
 * Time: 12:01
 * @property MainActiveRecord $model
 */
class MaintainApiController extends MainController
{
    public $model = null;
    public $model_class = null;

    const INVALID_REQUEST_DATA = 400;
    const SERVER_ERROR = 500;
    const UNAUTHORIZED = 401;
    const OK = 200;
    const NOT_FOUND = 404;
    public $enableCsrfValidation = false;


    public function behaviors() {
        return [
            'json-filter' => [
                'class' => FilterJson::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                    'register' => ['post'],
                    'info' => ['post']
                ]
            ],
            'auth' => [
                'class' => AuthKeyFilter::className(),
                'model_class' => Users::className(),
                'param' => 'api_key',
                'except' => ['login', 'register', 'fb-auth', 'test', 'groups', 'test-query', 'password-restore']
            ],
        ];
    }


    public function actions() {
        return [
            'login' => [
                'class' => ApiLogin::className(),
                'model_class' => Users::className(),
                'api_key' => 'api_key',
                'login_method' => 'apiLogin'
            ]
        ];
    }


    public function actionBeacons() {
        if($this->model instanceof Users) {
            $beacons = $this->model->getBeaconsQuery()->all();
            $result = [];
            foreach($beacons as $beacon) {
                $result[] = $beacon->toArray();
            }
            return $result;
        }
        else return $return[] = ['status' => self::NOT_FOUND];
    }


    public function actionEdit($id) {
        $model = Beacons::findOne($id);
        if($model->load(\Yii::$app->request->post())) {
            if($model->save()) {
                return ['status' => self::OK];
            }
            else return ['status' => self::SERVER_ERROR, 'errors' => $model->errors,'alert'=>Alert::getErrors()];
        }
        else return ['status' => self::INVALID_REQUEST_DATA];
    }


}