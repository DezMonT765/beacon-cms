<?php
use app\filters\AuthKeyFilter;
use app\filters\FilterJson;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 12.01.2017
 * Time: 16:04
 */

class BeaconApiController extends \dezmont765\yii2bundle\controllers\MainController {
    public function behaviors() {
        return [
            'json-filter' => [
                'class' => FilterJson::className(),
            ],

            'auth' => [
                'class' => AuthKeyFilter::className(),
            ],
        ];
    }

    public function actionUpdate($id) {

    }
}