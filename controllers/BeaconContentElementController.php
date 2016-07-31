<?php
namespace app\controllers;

use app\components\Alert;
use app\filters\BeaconContentElementsLayout;
use app\models\BeaconContentElements;
use app\models\BeaconContentElementsSearch;
use app\models\Beacons;
use dosamigos\editable\EditableAction;
use Exception;
use Yii;
use yii\web\Response;

/**
 * BeaconContentElementController implements the CRUD actions for BeaconContentElements model.
 */
class BeaconContentElementController extends MainController
{
    public $defaultAction = 'list';


    public function behaviors() {
        $behaviors = [
            'layout' => BeaconContentElementsLayout::className(),
        ];
        return $behaviors;
    }


    public function actions() {
        return [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => BeaconContentElements::className(),
                'forceCreate' => false
            ]
        ];
    }


    /**
     * Lists all BeaconContentElements models.
     * @return mixed
     */
    public function actionList() {
        $searchModel = new BeaconContentElementsSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();
        return $this->render('beacon-content-element-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new BeaconContentElements model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $model = new BeaconContentElements();
        $beacon = $this->findModel(Beacons::className(), $id);
        if($model->load(Yii::$app->request->post())) {
            $model->beacon_id = $beacon->id;
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('beacon-content-element-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing BeaconContentElements model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $content_id = Yii::$app->request->getQueryParam('content_id');
        $model = $this->findModel(BeaconContentElements::className(), $content_id);
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('beacon-content-element-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing BeaconContentElements model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $model = $this->findModel(BeaconContentElements::className(), $id);
            $model->delete();
        }
        catch(Exception $e) {
            Alert::addError('Item has not been deleted', $e->getMessage());
        }
        return $this->redirect(['list']);
    }


    public function actionMassDelete() {
        if(isset($_POST['keys'])) {
            foreach($_POST['keys'] as $key) {
                try {
                    $model = $this->findModel(BeaconContentElements::className(), $key);
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
        return $this->redirect(['list']);
    }


    public function actionAsAjax($id) {
        $model = $this->findModel(BeaconContentElements::className(), $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionList() {
        self::selectionList(BeaconContentElements::className(), 'name');
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionById() {
        self::selectionById(BeaconContentElements::className(), 'name');
    }

}
