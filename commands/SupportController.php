<?php
namespace app\commands;
use dezmont765\yii2bundle\components\Alert;
use app\models\Groups;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 06.05.2016
 * Time: 14:15
 */

class SupportController extends Controller {

    public function actionRegenerateUuid($is_force = false) {
        /** @var Groups[] $groups */
        $groups = Groups::find()->all();
        
        foreach($groups as $group) {
            $group->is_force_uuid = $is_force;
            $group->save();
        }
        Alert::varDumpAlert();
    }

    public function actionTest() {
        $session = \Yii::$app->session;
        var_dump($session);
    }
}