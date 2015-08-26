<?php
namespace app\controllers;
use app\models\Beacons;
use Yii;
use yii\web\HttpException;
use yii\web\Response;

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
 */

class ApiController extends MainController {

    const INVALID_REQUEST_DATA = 400;
    const OK = 200;
    const NOT_FOUND = 404;

    public function behaviors() 
    {
        return [];
    }

    public function actionBeaconData($beacons) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $return = [];
        if($beacons = json_decode($beacons,true))
        {
            foreach($beacons as $beacon){
                if(isset($beacon['uuid']) && isset($beacon['minor']) && isset($beacon['major']))
                {
                    $beacon = Beacons::find()->filterWhere(['uuid' => $beacon['uuid'], 'minor' => $beacon['minor'], 'major' => $beacon['major']])->one();
                    if($beacon instanceof Beacons) {
                        $beacon = $beacon->toArray(['title','description','absolutePicture']);
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
}