<?php
namespace app\controllers;

use app\models\BeaconPins;
use app\models\Beacons;
use app\models\Groups;
use app\models\NotPinnedBeacons;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 30.04.2015
 * Time: 6:41
 */
class NotPinnedBeaconController extends MainController
{

    public function behaviors() {
        return [
        ];
    }


    public function actionGetSelectionList() {
        $value = Yii::$app->request->getQueryParam('value');
        $group_id = Yii::$app->request->getQueryParam('group_id');
        $attribute = 'name';

        $query = Beacons::find()
                        ->filterWhere(['like', $attribute, $value])
                        ->joinWith(['groups' => function (ActiveQuery $query) use ($group_id) {
                            $query->andWhere([Groups::tableName() . '.id' => $group_id]);
                        }])
                        ->joinWith(['beaconPins'=>function(ActiveQuery $query){
                            $query->andWhere([BeaconPins::tableName().'.id'=>null]);
                        }]);
        $models = $query->all();
        $model_array = [];
        foreach($models as $model) {
            $model_array[] =
                ['id' => $model->id, 'text' => $model->name, 'url' => Url::to(['beacon/view', 'id' => $model->id])];
        }
        echo json_encode(['more' => false, 'results' => $model_array]);
    }


    public function actionGetSelectionById() {
        self::selectionById(NotPinnedBeacons::className());
    }
}