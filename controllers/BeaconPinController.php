<?php

namespace app\controllers;

use Yii;
use app\models\BeaconPins;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
        $model = BeaconPins::findOne(['id'=>$id]);
        if(!($model instanceof BeaconPins))
        {
            $model = new BeaconPins();
        }

        if($model->load(Yii::$app->request->post()))
        {
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
        $models = BeaconPins::find()->asArray()->all();
        foreach ($models as &$model)
        {
            $model['url'] = Url::to(['beacon/view','id'=>$model['id']]);
        }
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
