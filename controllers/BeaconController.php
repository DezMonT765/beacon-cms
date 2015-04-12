<?php

namespace app\controllers;

use app\commands\RbacController;
use app\filters\AdminBeaconLayout;
use app\filters\UserBeaconLayout;
use Yii;
use app\models\Beacons;
use app\models\BeaconsSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BeaconController implements the CRUD actions for Beacons model.
 */
class BeaconController extends MainController
{


    public $defaultAction = 'list';
    public function behaviors()
    {
        $behaviors =  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list','update','view','get-selection-by-id','get-selection-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create','delete'],
                        'allow' => true,
                        'roles'=>[RbacController::admin],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];

        $behaviors['layout'] =  ['class' => Yii::$app->user->can(RbacController::admin) ? AdminBeaconLayout::className() : UserBeaconLayout::className()];
        return $behaviors;
    }

    /**
     * Lists all Beacons models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new BeaconsSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();

        return $this->render('beacon-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Beacons model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        self::checkAccess(RbacController::update_beacon,['beacon'=>$model]);
        return $this->render('beacon-view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Beacons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Beacons();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('beacon-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Beacons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        self::checkAccess(RbacController::update_beacon,['beacon'=>$model]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('beacon-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Beacons model.
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
     * Finds the Beacons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Beacons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Beacons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetSelectionList()
    {
        parent::selectionList(Beacons::className(), 'name');
    }


    public function actionGetSelectionById()
    {
        self::selectionById(Beacons::className());
    }
}
