<?php
namespace app\controllers;

use dezmont765\yii2bundle\components\Alert;
use app\filters\KeyFilter;
use app\models\Groups;
use Yii;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 06.05.2016
 * Time: 18:21
 */
class CronController extends MainController
{
    public function behaviors() {
        return  [
            'key' => ['class' => KeyFilter::className(), 'key' => 'beacons@{2016}']
        ];
    }


    public function actionRegenerateUuid() {
        $is_force = Yii::$app->request->getQueryParam('is_force', 0);
        $groups = Groups::find()->all();
        foreach($groups as $group) {
            $group->is_force_uuid = $is_force;
            $group->save();
        }
        Alert::varDumpAlert();
        Alert::dropAlerts();
    }
}