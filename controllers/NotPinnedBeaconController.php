<?php
namespace app\controllers;
use app\models\NotPinnedBeacons;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 30.04.2015
 * Time: 6:41
 */

class NotPinnedBeaconController extends MainController
{

    public function behaviors()
    {
        return [

        ];
    }

    public function actionGetSelectionList()
    {
        $model_class = NotPinnedBeacons::className();
        $value = Yii::$app->request->getQueryParam('value');
        $attribute = 'name';
        $model = new $model_class;
        $models = $model->searchByAttribute($attribute,$value);
        $model_array = [];
        foreach ($models as $model)
        {
            $model_array[] =['id'=>$model->id,'text'=> $model->name ,'url' => Url::to(['beacon/view','id'=>$model->id]) ];
        }
        echo json_encode(['more'=>false,'results'=>$model_array]);
    }


    public function actionGetSelectionById()
    {
        self::selectionById(NotPinnedBeacons::className());
    }
}