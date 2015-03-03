<?php

namespace app\controllers;

use app\components\AdminLayout;
use app\components\UserLayout;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends MainController
{
    public $layout = 'tabbedLayout';

    public function init()
    {
        $this->activeMap = [
            'index' => [UserLayout::user_list => true],
            'create' => [UserLayout::user_create => true],
            'update' => [UserLayout::user_update => true],
            'view' => [UserLayout::user_view => true],
        ];
    }
    public function behaviors()
    {
        $behaviors = array_merge(parent::behaviors(),[
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update','view','delete'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['index','create'],
                        'allow' => true,
                        'roles' => [RbacController::superAdmin],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

        ]);

        $behaviors['layout'] =  ['class' => Yii::$app->user->can(RbacController::superAdmin) ? AdminLayout::className() : UserLayout::className()];
        return $behaviors;
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Users model.
     * @param integer $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can(RbacController::manageUserAccount,['user_id'=>$model->id]))
            throw new ForbiddenHttpException('You can not manage this account');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can(RbacController::manageUserAccount,['user_id'=>$model->id]))
            throw new ForbiddenHttpException('You can not manage this account');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can(RbacController::manageUserAccount,['user_id'=>$model->id]))
            throw new ForbiddenHttpException('You can not manage this account');
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
