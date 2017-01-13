<?php
namespace app\controllers;

use app\models\ClientBeacons;
use app\models\ClientBeaconSearch;
use dezmont765\yii2bundle\components\Alert;
use dosamigos\editable\EditableAction;
use Exception;
use Yii;
use yii\web\Response;

/**
 * ClientBeaconController implements the CRUD actions for ClientBeacons model.
 */
class ClientBeaconController extends MainController
{
    public $defaultAction = 'list';


    public function behaviors() {
        $behaviors = [
        ];
        return $behaviors;
    }


    public function actions() {
        return [
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
    public function actionList() {
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
    public function actionView($id) {
        $model = $this->findModel(ClientBeacons::className(), $id);
        return $this->render('client-beacon-view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new ClientBeacons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ClientBeacons();
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
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
    public function actionUpdate($id) {
        $model = $this->findModel(ClientBeacons::className(), $id);
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
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
    public function actionDelete($id) {
        $url = Yii::$app->request->getQueryParam('url');
        try {
            $model = $this->findModel(ClientBeacons::className(), $id);
            $model->delete();
        }
        catch(Exception $e) {
            Alert::addError('Item has not been deleted', $e->getMessage());
        }
        return $this->redirect($url);
    }


    public function actionMassDelete() {
        $url = Yii::$app->request->getQueryParam('url');
        if(isset($_POST['keys'])) {
            foreach($_POST['keys'] as $key) {
                try {
                    $model = $this->findModel(ClientBeacons::className(), $key);
                    if($model) {
                        if($model->delete()) {
                            Alert::addSuccess("Items has been successfully deleted");
                        }
                    }
                }
                catch(Exception $e) {
                    Alert::addError('Item has not been deleted', $e->getMessage());
                }
            }
        }
        return $this->redirect($url);
    }


    public function actionAsAjax($id) {
        $model = $this->findModel(ClientBeacons::className(), $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionList() {
        self::selectionList(ClientBeacons::className(), 'name');
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionById() {
        self::selectionById(ClientBeacons::className(), 'name');
    }

}
