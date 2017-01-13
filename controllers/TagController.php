<?php
namespace app\controllers;

use app\commands\RbacController;
use dezmont765\yii2bundle\components\Alert;
use app\filters\FilterJson;
use app\filters\TagLayout;
use app\models\Tags;
use app\models\TagsSearch;
use dezmont765\yii2bundle\actions\SelectionByAttributeAction;
use dezmont765\yii2bundle\actions\SelectionListAction;
use dosamigos\editable\EditableAction;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * TagController implements the CRUD actions for Tags model.
 */
class TagController extends MainController
{
    public $defaultAction = 'list';


    public function behaviors() {
        $behaviors = [
            'json-filter' => [
                'class' => FilterJson::className(),
                'only' => ['json-list']
            ],
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['json-list'],
                'rules' => [
                    [
                        'actions' => ['list', 'map', 'update', 'view', 'get-selection-by-id', 'get-selection-list',
                                      'save-redactor-image'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => [RbacController::admin],
                    ],
                ],
            ],
        ];
        $behaviors['layout'] = TagLayout::className();
        return $behaviors;
    }


    public function actions() {
        return [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => Tags::className(),
                'forceCreate' => false
            ],
            'get-selection-by-id' => [
                'class' => SelectionByAttributeAction::className(),
                'model_class' => Tags::className(),
                'is_multiple' => true,
            ],
            'get-selection-list' => [
                'class' => SelectionListAction::className(),
                'model_class' => Tags::className(),
            ]
        ];
    }


    /**
     * Lists all Tags models.
     * @return mixed
     */
    public function actionList() {
        $searchModel = new TagsSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();
        return $this->render('tag-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Tags model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel(Tags::className(), $id);
        return $this->render('tag-view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Tags();
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('tag-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel(Tags::className(), $id);
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('tag-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $model = $this->findModel(Tags::className(), $id);
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
                    $model = $this->findModel(Tags::className(), $key);
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
        $model = $this->findModel(Tags::className(), $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }


    public function actionJsonList() {
        $models = Tags::find()->asArray()->all();
        return $models;
    }


}
