<?php
namespace app\controllers;
use app\filters\AuthKeyFilter;
use app\helpers\Helper;
use app\models\Beacons;
use app\filters\FilterJson;
use app\models\ClientBeacons;
use app\models\ClientUsers;
use app\models\Statistics;
use Yii;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;
use yii\web\ErrorHandler;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.07.2015
 * Time: 14:00
 *
 * Example of request :
 * http://yii2.test/api/beacon-data/?beacons=[{"uuid":"qwer-1234-asd4-vs1qe-mm3a","major":"777","minor": "45435" },{"uuid": "qwer-1234-asd4-vs1qe-mm3a","major": "123", "minor": "3214"} ]
 *
 * Examples of response :
 *
 * [{"title":"Nice beaconsss","description":"Simple beacon for test","absolutePicture":"http://yii2.test/beacon_images/1/PDJaGxSxhms1PTiT.png","status":200},{"status":400}]
   {"name":"Bad Request","message":"Invalid data","code":0,"status":400,"type":"yii\\web\\HttpException"}
 * @property ClientUsers $client_user
 */

class ApiController extends MainController {

    const INVALID_REQUEST_DATA = 400;
    const UNAUTHORIZED = 401;
    const OK = 200;
    const NOT_FOUND = 404;
    public $enableCsrfValidation = false;
    public function init() {
        $handler = new ErrorHandler();
        $handler->errorAction = 'api/error';
        Yii::$app->set('errorHandler',$handler);
        $handler->register();
    }

    public $client_user = null;

    public function actions () {
        return [
            'error' => ErrorAction::className()
        ];
    }

    public function behaviors() 
    {
        return [
            'json-filter' => FilterJson::className(),
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login'  => ['post'],
                    'register'   => ['post'],
                    'statistic' => ['post']
                ]
            ],
            'auth' => [
                'class' => AuthKeyFilter::className(),
                'except' => ['login','register']
            ],

        ];
    }

    public function actionBeaconData($beacons) {


        $return = [];
        if($beacons = json_decode($beacons,true))
        {
            foreach($beacons as $beacon){
                if(isset($beacon['uuid']) && isset($beacon['minor']) && isset($beacon['major']))
                {
                    $beacon = Beacons::find()->filterWhere(['uuid' => $beacon['uuid'], 'minor' => $beacon['minor'], 'major' => $beacon['major']])->one();
                    if($beacon instanceof Beacons) {
                        $beacon_statistic = new ClientBeacons();
                        $beacon_statistic->beacon_id = $beacon->id;
                        if($this->client_user instanceof ClientUsers)
                            $beacon_statistic->client_id = $this->client_user->id;
                        $beacon_statistic->save();
                        $beacon = $beacon->toArray();
                        $beacon['status'] = self::OK;
                    }
                    else $beacon = ['status'=>self::NOT_FOUND];
                    $return[] = $beacon;
                }
                else {
                    $return[] = ['status'=>self::INVALID_REQUEST_DATA];
                }
            }
            return $return;
        }
        else throw new HttpException(400,'Invalid data');
    }

        public function actionLogin() {
           $model = new ClientUsers();
            if($model->load(Yii::$app->request->post()))
            {
                if($model->login())
                {
                    return ['auth_key' => $model->auth_key];
                }
            }
            throw new HttpException(401,'You have not been authorized ' . Helper::recursive_implode($model->errors,',',false,false));
        }

    public function actionRegister() {
        $model = new ClientUsers();
        if($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                return ['auth_key'=>$model->auth_key];
            }
        }
        throw new HttpException(400,'Your credentials are invalid ' . Helper::recursive_implode($model->errors,',',false,false));
    }

    public function actionStatistic() {
        $model = new Statistics();
        if($model->load(Yii::$app->request->post())) {
            if($this->client_user instanceof ClientUsers)
                $model->client_id = $this->client_user->id;
            if(!$model->save()) {
                throw new HttpException(500,'Statistic has\'nt been saved. ' . Helper::recursive_implode($model->errors,',',false,false));
            }
        } else {
            throw new HttpException(400,'Invalid request');
        }

    }


}