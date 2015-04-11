<?php

namespace app\controllers;

use app\commands\RbacController;
use app\components\Alert;
use app\filters\GroupLayout;
use Yii;
use app\models\Groups;
use app\models\GroupSearch;
use yii\console\Response;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupController implements the CRUD actions for Groups model.
 */
class GroupController extends MainController
{
    public $defaultAction = 'list';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [RbacController::admin],
                    ],
                ],
            ],
            'layout' => [
                'class' => GroupLayout::className(),
            ],
        ];
    }


    /**
     * Lists all Groups models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('group-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Groups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('group-view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionMassDelete()
    {
        if(isset($_POST['keys']))
        {
            foreach ($_POST['keys'] as $key)
            {
                $model = $this->findModel($key);
                if($model)
                {
                    if($model->delete()){
                        Alert::addSuccess("Items has been successfully deleted");
                    }
                }
            }
        }
    }

    public function actionAsAjax($id)
    {
        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $model->toArray();
    }


    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Groups();
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('group-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('group-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Groups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['list']);
    }


    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = Groups::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetSelectionList()
    {
        parent::selectionList(Groups::className(), 'name');
    }


    public function actionGetSelectionById()
    {
        self::selectionById(Groups::className());
    }
}
