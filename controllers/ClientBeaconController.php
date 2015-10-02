<?php

namespace app\controllers;

use Exception;
use Yii;
use app\models\ClientBeacons;
use app\models\ClientBeaconSearch;
use app\controllers\MainController;
use console\controllers\RbacController;
use app\components\Alert;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use dosamigos\editable\EditableAction;

/**
 * ClientBeaconController implements the CRUD actions for ClientBeacons model.
 */
class ClientBeaconController extends MainController
{
    public $defaultAction = 'list';
    public function behaviors()
    {
        $behaviors = [
            'layout' => ClientBeaconLayout::className(),
        ];
        return $behaviors;
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => ClientBeacons::className(),
                'forceCreate' => false
            ]
        ];
    }

    /**
     * Lists all ClientBeacons models.
     * @return mixed
     */
    public function actionList()
    {
                $searchModel = new ClientBeaconSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();

        return $this->render('client-beacon-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }

    /**
     * Displays a single ClientBeacons model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
         $model = $this->findModel(ClientBeacons::className(),$id);
         return $this->render('client-beacon-view', [
            'model' => $model,
         ]);
    }

    /**
     * Creates a new ClientBeacons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClientBeacons();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('client-beacon-form', [
                   'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClientBeacons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(ClientBeacons::className(),$id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
                return $this->render('client-beacon-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClientBeacons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $model = $this->findModel(ClientBeacons::className(),$id);
            $model->delete();
        }
        catch(Exception $e) {
            Alert::addError('Item has not been deleted', $e->getMessage());
        }
        return $this->redirect(['list']);
    }


    public function actionMassDelete()
    {
        if(isset($_POST['keys']))
        {
            foreach ($_POST['keys'] as $key)
            {
                try {
                    $model = $this->findModel(ClientBeacons::className(), $key);
                    if($model)
                    {
                        if($model->delete()){
                            Alert::addSuccess("Items has been successfully deleted");
                        }
                    }
                }
                catch(Exception $e) {
                    Alert::addError('Item has not been deleted',$e->getMessage());
                }
            }
        }
        return $this->redirect(['list']);
    }

    public function actionAsAjax($id)
    {
        $model = $this->findModel(ClientBeacons::className(),$id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionList()
    {
            self::selectionList(ClientBeacons::className(),'name');
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionById()
    {
        self::selectionById(ClientBeacons::className(),'name');
    }

}
