<?php
namespace app\controllers;

use app\actions\SaveEditorImage;
use app\commands\RbacController;
use dezmont765\yii2bundle\components\Alert;
use app\filters\BeaconLayout;
use app\filters\BeaconManageLayout;
use app\models\BeaconContentElementsSearch;
use app\models\BeaconMapLoad;
use app\models\BeaconPins;
use app\models\Beacons;
use app\models\BeaconsSearch;
use app\models\Groups;
use app\models\Users;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * BeaconController implements the CRUD actions for Beacons model.
 */
class BeaconController extends MainController
{


    public $defaultAction = 'list';


    public function actions() {
        return [
            'save-redactor-image' => [
                'class' => SaveEditorImage::className(),
                'model_class' => Beacons::className(),
            ]
        ];
    }


    public function behaviors() {
        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list', 'map', 'update', 'view', 'get-selection-by-id', 'get-selection-list',
                                      'save-redactor-image'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'delete', 'map', 'content-elements'],
                        'allow' => true,
                        'roles' => [RbacController::admin],
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
        $behaviors['layout'] = [
            'class' => BeaconLayout::className()
        ];
        $behaviors['manage-layout'] = [
            'class' => BeaconManageLayout::className(),
            'only' => ['update']
        ];
        return $behaviors;
    }


    /**
     * Lists all Beacons models.
     * @return mixed
     */
    public function actionList() {
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
    public function actionView($id) {
        $model = $this->findModel(Beacons::className(), $id);
        self::checkAccess(RbacController::update_beacon, ['beacon' => $model]);
        return $this->render('beacon-view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Beacons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Beacons();
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
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
    public function actionUpdate($id) {
        $model = $this->findModel(Beacons::className(), $id);
        self::checkAccess(RbacController::update_beacon, ['beacon' => $model]);
        if($model->load(Yii::$app->request->post())) {
            if(!Yii::$app->user->can(RbacController::admin)) {
                $model->groupToBind = '';
            }
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('beacon-form', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Beacons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel(Beacons::className(), $id)->delete();
        return $this->redirect(['list']);
    }


    public function actionGetSelectionList() {
        parent::selectionList(Beacons::className(), 'name');
    }


    public function actionGetSelectionById() {
        self::selectionById(Beacons::className());
    }


    public function actionMap($group_id = null) {
        $model = new BeaconPins();
        $group = Groups::findOne($group_id);
        $map_provider = new ArrayDataProvider([
                                                  'allModels' => $group->map,
                                                  'pagination' => [
                                                      'pageSize' => 1
                                                  ],
                                              ]);
        if(!$group instanceof Groups) {
            $user = Users::getLogged(true);
            if(isset($user->groups[0])) {
                $group = $user->groups[0];
            }
        }
        if(!$group instanceof Groups) {
            throw new ForbiddenHttpException('Chosen group doesn\'t contain any map. 
             Please contact to support team, or add the map to the group by yourself.
             Also please ensure that you belong at least to one group');
        }
        return $this->render('beacon-map', ['model' => $model, 'group' => $group, 'map_provider' => $map_provider]);
    }


    /**
     *
     */
    public function actionSaveMap() {
        $model = new BeaconMapLoad();
        $model->load(Yii::$app->request->post());
        if($model->saveMap()) {
            Alert::addSuccess(Yii::t('messages', ':map_load'));
        }
        else Alert::addError(Yii::t('messages', ':map_not_load'));
        return $this->redirect(['map']);
    }


    public function actionContentElements($id) {
        $searchModel = new BeaconContentElementsSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->beacon_id = $id;
        $dataProvider = $searchModel->search();
        return $this->render('beacon-content-element-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
