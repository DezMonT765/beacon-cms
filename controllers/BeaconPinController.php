<?php

namespace app\controllers;

use app\models\BeaconMaps;
use app\models\Groups;
use app\models\Users;
use Yii;
use app\models\BeaconPins;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\User;

/**
 * BeaconPinController implements the CRUD actions for BeaconPins model.
 */
class BeaconPinController extends MainController
{
    public function behaviors()
    {
        return [

        ];
    }

    public function actionSave()
    {
        $id = isset($_POST['BeaconPins']['id']) ? $_POST['BeaconPins']['id'] : null;
        $group_file_id = Yii::$app->request->getQueryParam('group_file_id');
        $model = BeaconPins::findOne(['id'=>$id]);
        if(!($model instanceof BeaconPins))
        {
            $model = new BeaconPins();
        }

        if($model->load(Yii::$app->request->post()))
        {
            $model->group_file_id = $group_file_id;
            if($model->save())
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success'=>true];
            }
            else throw new ServerErrorHttpException('Pin not saved');
        }
        else throw new ServerErrorHttpException('Pin not saved');
    }

    public function actionJson()
    {
        /** @var BeaconPins $model */
        $group_id = Yii::$app->request->getQueryParam('group_id');
        $group_file_id = Yii::$app->request->getQueryParam('group_file_id');
        $models = BeaconPins::find()
            ->andWhere(['group_file_id'=>$group_file_id])
            ->joinWith(['beacon'=>function(ActiveQuery $query) use($group_id) {
                $query->joinWith(['groups'=>function(ActiveQuery $query) use($group_id) {
                    $query->andFilterWhere([Groups::tableName(). '.id' => $group_id]);
                }]);
            }])->indexBy('name')->select([ BeaconPins::tableName(). '.id',BeaconPins::tableName().'.name','x','y'])->all();

        $beacon_pin_array = ['pins'=> $models];
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $beacon_pin_array;
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = $this->findModel(BeaconPins::className(),$id);
        if($model->delete())
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success'=>true];
        }
        else throw new ServerErrorHttpException('Beacon pin not removed');
    }





}
