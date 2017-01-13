<?php

namespace app\controllers;

use app\commands\RbacController;
use app\filters\AdminLayout;
use app\filters\AdminUserLayout;
use app\filters\AdminUserManageLayout;
use app\filters\UserLayout;
use app\actions\UserEditableAction;
use app\filters\UserManageLayout;
use app\models\BeaconsSearch;
use dezmont765\yii2bundle\components\Alert;
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

    public $defaultAction = 'list';

    public function behaviors()
    {
        $behaviors = array_merge(parent::behaviors(),[
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update','view','delete','available-groups','ajax-update'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['list','create'],
                        'allow' => true,
                        'roles' => [RbacController::create_profile],
                    ],
                    [
                        'actions' => ['beacons'],
                        'allow'=>true,
                        'roles' => [RbacController::admin]
                    ]
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

        ]);

        $behaviors['layout'] =  Yii::$app->user->can(RbacController::admin) ?
            ['class' => AdminUserLayout::className(),'only'=>['list','create']] :
            ['class' => UserLayout::className()];

        if(Yii::$app->user->can(RbacController::admin))
        {
            $behaviors['manage-layout'] = ['class' => AdminUserManageLayout::className(), 'except' => ['list', 'create']];
        }
        return $behaviors;
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => UserEditableAction::className(),
                'modelClass' => Users::className(),
                'forceCreate' => false
            ]
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('user-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBeacons($id)
    {
        $searchModel = new BeaconsSearch();

        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search($id);

        return $this->render('/beacon/beacon-list', [
            'searchModel' => new BeaconsSearch(),
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
        $model = $this->findModel(Users::className(),$id);
        self::checkAccess(RbacController::update_profile,['user'=>$model]);
        return $this->render('user-view', [
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
        $model = new Users(['scenario'=>'create']);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('user-form', [
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
        $model = $this->findModel(Users::className(),$id);
        self::checkAccess(RbacController::update_profile,['user'=>$model]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('user-form', [
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
        $model = $this->findModel(Users::className(),$id);
        self::checkAccess(RbacController::delete_profile,['user'=>$model]);
        $model->delete();
        return $this->redirect(['list']);
    }



    public function actionEditableRoles()
    {
        /**@var Users $user*/
        $user = Yii::$app->user->identity;
        return json_encode($user->getEditableRoles());
    }


}
