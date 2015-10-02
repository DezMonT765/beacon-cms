<?php

namespace app\controllers;

use Exception;
use Yii;
use app\models\Info;
use app\models\InfoSearch;
use app\controllers\MainController;
use console\controllers\RbacController;
use app\components\Alert;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use dosamigos\editable\EditableAction;

/**
 * InfoController implements the CRUD actions for Info model.
 */
class InfoController extends MainController
{
    public $defaultAction = 'list';
    public function behaviors()
    {
        $behaviors = [
        ];
        return $behaviors;
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => Info::className(),
                'forceCreate' => false
            ]
        ];
    }

    /**
     * Lists all Info models.
     * @return mixed
     */
    public function actionList()
    {
                $searchModel = new InfoSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();

        return $this->render('info-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }

    /**
     * Displays a single Info model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
         $model = $this->findModel(Info::className(),$id);
         return $this->render('info-view', [
            'model' => $model,
         ]);
    }

    /**
     * Creates a new Info model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Info();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('info-form', [
                   'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Info model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(Info::className(),$id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
                return $this->render('info-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Info model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $url = Yii::$app->request->getQueryParam('url');
        try
        {
            $model = $this->findModel(Info::className(),$id);
            $model->delete();
        }
        catch(Exception $e) {
            Alert::addError('Item has not been deleted', $e->getMessage());
        }
        return $this->redirect($url);
    }


    public function actionMassDelete()
    {
        $url = Yii::$app->request->getQueryParam('url');
        if(isset($_POST['keys']))
        {
            foreach ($_POST['keys'] as $key)
            {
                try {
                    $model = $this->findModel(Info::className(), $key);
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
        return $this->redirect($url);
    }

    public function actionAsAjax($id)
    {
        $model = $this->findModel(Info::className(),$id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionList()
    {
            self::selectionList(Info::className(),'name');
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionById()
    {
        self::selectionById(Info::className(),'name');
    }

}
