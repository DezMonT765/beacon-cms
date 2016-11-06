<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 20:03
 */

namespace app\controllers;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class MainController extends Controller
{
    const ACCESS_FILTER = 'access';
    public function behaviors()
    {
        return [
            self::ACCESS_FILTER => [
                'class' => AccessControl::className(),
            ],
        ];
    }
    public $activeMap = [];

    public function getTabsActivity()
    {
        return isset($this->activeMap[$this->action->id]) ? $this->activeMap[$this->action->id] : [];
    }

    public static  function checkAccess($permission,array $params = [])
    {
        if(Yii::$app->user->can($permission,$params))
            return true;
        else
        {
            if (Yii::$app->user->getIsGuest()) {
                Yii::$app->user->loginRequired();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }

    }


    public function selectionList($model_class,$attribute,callable $return_wrap = null)
    {
        /** @var ActiveRecord $model_class */
        $value = Yii::$app->request->getQueryParam('value');
        $model = new $model_class;
        $models = $model->searchByAttribute($attribute,$value);
        $model_array = [];
        foreach ($models as $model)
        {
            $model_array[] =['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->$attribute : $return_wrap($model) ];
        }
        echo json_encode(['more'=>false,'results'=>$model_array]);
    }

    public function selectionById($model_class,$attribute = 'name', callable $return_wrap = null)
    {
        /** @var ActiveRecord $model_class */
        $id = Yii::$app->request->getQueryParam('id');
        $model = new $model_class;
        $ids = explode(',',$id);
        $models = $model->searchByIds($ids);
        $model_array = [];
        if(count($models) == 1)
        {
            $model = array_shift($models);
            $model_array = ['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->$attribute : $return_wrap($model)];
        }
        else
        {
            foreach ($models as $model)
            {
                $model_array[] =['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->name : $return_wrap($model)];
            }
        }
        echo json_encode(['more'=>false,'results'=>$model_array]);
    }

    /* @param $model_class
    * @param $id
    * @throws NotFoundHttpException
    * @return mixed  $model
    */
    protected function findModel($model_class,$id)
    {
        if (($model = $model_class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}