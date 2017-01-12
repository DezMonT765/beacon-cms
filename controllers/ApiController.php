<?php
namespace app\controllers;

use app\filters\AuthKeyFilter;
use app\filters\FilterJson;
use app\helpers\Helper;
use app\models\BeaconPins;
use app\models\Beacons;
use app\models\ClientBeacons;
use app\models\ClientUsers;
use app\models\Groups;
use app\models\Info;
use Yii;
use yii\db\ActiveQuery;
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
 * http://yii2.test/api/beacon-data/?beacons=[{"uuid":"qwer-1234-asd4-vs1qe-mm3a","major":"777","minor": "45435"
 * },{"uuid": "qwer-1234-asd4-vs1qe-mm3a","major": "123", "minor": "3214"} ]
 *
 * Examples of response :
 *
 * [{"title":"Nice beaconsss","description":"Simple beacon for
 * test","absolutePicture":"http://yii2.test/beacon_images/1/PDJaGxSxhms1PTiT.png","status":200},{"status":400}]
 * {"name":"Bad Request","message":"Invalid data","code":0,"status":400,"type":"yii\\web\\HttpException"}
 * @property ClientUsers $client_user
 */
class ApiController extends MainController
{

    const INVALID_REQUEST_DATA = 400;
    const UNAUTHORIZED = 401;
    const OK = 200;
    const NOT_FOUND = 404;
    public $enableCsrfValidation = false;


    public function init() {
        $handler = new ErrorHandler();
        $handler->errorAction = 'api/error';
        Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }


    public $client_user = null;


    public function actions() {
        return [
            'error' => ErrorAction::className()
        ];
    }


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
                'except' => ['login', 'register', 'fb-auth', 'test', 'groups', 'test-query', 'password-restore']
            ],
        ];
    }


    public function actionBeaconData($beacons) {
        $return = [];
        if($beacons = json_decode($beacons, true)) {
            foreach($beacons as $beacon) {
                if(isset($beacon['uuid']) && isset($beacon['minor']) && isset($beacon['major'])) {
                    $query = Beacons::find()->filterWhere([Beacons::tableName() . '.uuid' => $beacon['uuid'],
                                                           Beacons::tableName() . '.minor' => $beacon['minor'],
                                                           Beacons::tableName() . '.major' => $beacon['major']]);
                    if($this->client_user instanceof ClientUsers) {
                        $client_group_ids = $this->client_user->getGroupIds();
                        if(is_array($client_group_ids) && count($client_group_ids) > 0) {
                            $query->joinWith(['groups' => function (ActiveQuery $query) use ($client_group_ids) {
                                $query->andWhere(['in', Groups::tableName() . '.id', $client_group_ids]);
                            }]);
                        }
                        else {
                            $query->joinWith(['groups' => function (ActiveQuery $query) {
                                $query->andWhere([Groups::tableName() . '.name' => 'Default']);
                            }]);
                        }
                    }
                    $beacon = $query->one();
                    if($beacon instanceof Beacons) {
                        $beacon_statistic = new ClientBeacons();
                        $beacon_statistic->beacon_id = $beacon->id;
                        if($this->client_user instanceof ClientUsers) {
                            $beacon_statistic->client_id = $this->client_user->id;
                        }
                        $beacon_statistic->save();
                        $beacon = $beacon->toArray();
                        $beacon['status'] = self::OK;
                    }
                    else $beacon = ['status' => self::NOT_FOUND];
                    $return[] = $beacon;
                }
                else {
                    $return[] = ['status' => self::INVALID_REQUEST_DATA];
                }
            }
            return $return;
        }
        else throw new HttpException(400, 'Invalid data');
    }


    public function actionMap($beacon) {
        $return = [];
        if($beacon = json_decode($beacon, true)) {
            if(isset($beacon['uuid']) && isset($beacon['minor']) && isset($beacon['major'])) {
                $query = Beacons::find()->filterWhere([Beacons::tableName() . '.uuid' => $beacon['uuid'],
                                                       Beacons::tableName() . '.minor' => $beacon['minor'],
                                                       Beacons::tableName() . '.major' => $beacon['major']]);
                if($this->client_user instanceof ClientUsers) {
                    $client_group_ids = $this->client_user->getGroupIds();
                    if(is_array($client_group_ids) && count($client_group_ids) > 0) {
                        $query->joinWith(['groups' => function (ActiveQuery $query) use ($client_group_ids) {
                            $query->andWhere(['in', Groups::tableName() . '.id', $client_group_ids]);
                        }]);
                    }
                    else {
                        $query->joinWith(['groups' => function (ActiveQuery $query) {
                            $query->andWhere([Groups::tableName() . '.name' => 'Default']);
                        }]);
                    }
                }
                $beacon = $query->one();
                if($beacon instanceof Beacons) {
                    $beaconPin = $beacon->beaconPins;
                    $beaconMap = $beaconPin->groupFile->beaconMap;
                    $mapId = $beaconPin->group_file_id;
                    $other = BeaconPins::find()->where(['group_file_id'=>$mapId])->asArray()->all();
                    $beacon = [
                        'current' => [
                            'x' => $beacon->getX(),
                            'y' => $beacon->getY(),
                        ],
                        'walls' => $beaconMap->map,
                        'other' => $other
                    ];
                }
                else  $beacon = ['status' => self::NOT_FOUND];
                $return[] = $beacon;
            }
            else {
                $return[] = ['status' => self::INVALID_REQUEST_DATA];
            }
        }
        return $return;
    }


    public function actionLogin() {
        $model = new ClientUsers();
        if($model->load(Yii::$app->request->post())) {
            if($model->login()) {
                $user = ClientUsers::findByEmail($model->email);
                if($user instanceof ClientUsers) {
                    $user->group_ids = $model->group_ids;
                    $user->save();
                }
                return ['auth_key' => $model->auth_key];
            }
        }
        throw new HttpException(401,
                                'You have not been authorized ' . Helper::recursive_implode($model->errors, ',', false,
                                                                                            false));
    }


    public function actionRegister() {
        $model = new ClientUsers();
        if($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                return ['auth_key' => $model->auth_key];
            }
        }
        throw new HttpException(400,
                                'Your credentials are invalid ' . Helper::recursive_implode($model->errors, ',', false,
                                                                                            false));
    }


    public function actionPasswordRestore() {
        $mail = mail('dezmont765@gmail.com','lama','hura');
        var_dump($mail);
        $model = new ClientUsers();
        if($model->load(Yii::$app->request->post())) {
            if($model->sendPasswordRestoreEmail()) {
                return ['status' => true];
            }
        }
        return ['status' => false];
    }


    public function actionFbAuth() {
        $model = new ClientUsers();
        if($model->load(Yii::$app->request->post())) {
            if($model->fbAuth()) {
                $user = ClientUsers::findByEmail($model->email);
                if($user instanceof ClientUsers) {
                    $user->group_ids = $model->group_ids;
                    $user->save();
                }
                return ['auth_key' => $model->auth_key];
            }
        }
        throw new HttpException(400,
                                'Your credentials are invalid ' . Helper::recursive_implode($model->errors, ',', false,
                                                                                            false));
    }


    public function actionInfo() {
        $info = file_get_contents('php://input');
        if($info = json_decode($info, true)) {
            foreach($info as $key => $value) {
                $model = Info::findOne(['key' => $key, 'client_id' => $this->client_user->id]);
                if(!($model instanceof Info)) {
                    $model = new Info();
                }
                $model->key = $key;
                $model->value = $value;
                if($this->client_user instanceof ClientUsers) {
                    $model->client_id = $this->client_user->id;
                }
                $model->save();
            }
        }
        else throw new HttpException(400, 'Invalid info');
    }


    public function actionGroups() {
        $return = [];
        $groups = Groups::find()->all();
        foreach($groups as $group) {
            $return[] = $group->toArray(['id', 'name', 'uuid']);
        }
        return $return;
    }


    public function actionBeaconsList() {
        $query = Beacons::find();
        if($this->client_user instanceof ClientUsers) {
            $client_group_ids = $this->client_user->getGroupIds();
            if(is_array($client_group_ids) && count($client_group_ids) > 0) {
                $query->joinWith(['groups' => function (ActiveQuery $query) use ($client_group_ids) {
                    $query->andWhere(['in', Groups::tableName() . '.id', $client_group_ids]);
                }]);
            }
            else {
                $query->joinWith(['groups' => function (ActiveQuery $query) {
                    $query->andWhere([Groups::tableName() . '.name' => 'Default']);
                }]);
            }
        }
        $beacon = $query->asArray()->all();
        return $beacon;
    }


}