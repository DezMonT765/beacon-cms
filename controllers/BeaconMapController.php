<?php
namespace app\controllers;

use app\commands\RbacController;
use app\models\BeaconMaps;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 06.11.2016
 * Time: 17:11
 */
class BeaconMapController extends MainController
{
    const ALL_ACCESS_RULE = 'all_access_rules';


    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
            self::ACCESS_FILTER => [
                'class' => AccessControl::className(),
                'rules' => [
                    self::ALL_ACCESS_RULE => [
                        'allow' => true,
                        'roles' => [RbacController::user],
                    ]
                ],
            ],
        ]);
    }


    public function actionSave() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $group_file_id = Yii::$app->request->getQueryParam('group_file_id');
        $map = Yii::$app->request->getBodyParam('data');
        if($map = json_decode($map)) {
            $model = BeaconMaps::findOne(['id' => $group_file_id]);
            if(!$model instanceof BeaconMaps) {
                $model = new BeaconMaps();
            }
            $model->id = $group_file_id;
            $model->map = $map;
            if($model->save()) {
                return ['success' => true];
            }
            else return ['success' => false, 'errors' => $model->errors];
        }
        else throw new HttpException(401, 'Wrong data format');
    }


    public function actionGet($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = BeaconMaps::findOne($id);
        if($model instanceof BeaconMaps) {
            return $model->map;
        }
        else return null;
    }


}