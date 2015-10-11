<?php

namespace app\controllers;

use app\filters\ClientUserLayout;
use app\filters\ClientUserManageLayout;
use app\models\ClientBeaconSearch;
use app\models\InfoSearch;
use Exception;
use Yii;
use app\models\ClientUsers;
use app\models\ClientUsersSearch;
use app\components\Alert;
use yii\web\Response;
use dosamigos\editable\EditableAction;

/**
 * ClientUserController implements the CRUD actions for ClientUsers model.
 */
class ClientUserController extends MainController
{
    public $defaultAction = 'list';
    public function behaviors()
    {
        $behaviors = [
        ];
        $behaviors['layout'] = ['class' => ClientUserLayout::className(),'only'=>['list','create']];
        $behaviors['manage-layout'] = ['class' => ClientUserManageLayout::className(), 'except' => ['list', 'create']];
        return $behaviors;
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => ClientUsers::className(),
                'forceCreate' => false
            ]
        ];
    }

    public function actionBeacons($id)
    {
        $searchModel = new ClientBeaconSearch();

        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->client_id = $id;
        $dataProvider = $searchModel->search();

        return $this->render('/client-beacon/client-beacon-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionInfo($id) {
        $searchModel = new InfoSearch();
        $searchModel->client_id = $id;
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();
        return $this->render('/info/info-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all ClientUsers models.
     * @return mixed
     */
    public function actionList()
    {
                $searchModel = new ClientUsersSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();

        return $this->render('client-user-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }

    /**
     * Displays a single ClientUsers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
         $model = $this->findModel(ClientUsers::className(),$id);
         return $this->render('client-user-view', [
            'model' => $model,
         ]);
    }

    /**
     * Creates a new ClientUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClientUsers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('client-user-form', [
                   'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClientUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(ClientUsers::className(),$id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
                return $this->render('client-user-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClientUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $model = $this->findModel(ClientUsers::className(),$id);
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
                    $model = $this->findModel(ClientUsers::className(), $key);
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
        $model = $this->findModel(ClientUsers::className(),$id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionList()
    {
            self::selectionList(ClientUsers::className(),'name');
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionById()
    {
        self::selectionById(ClientUsers::className(),'name');
    }

}
