<?php
namespace app\controllers;
use app\commands\RbacController;
use app\filters\SiteLayout;
use app\models\LoginForm;
use app\models\PromoForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 06.01.2016
 * Time: 12:51
 */

class PromoController extends MainController {

    public function behaviors()
    {
        $behaviors =  array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'layout' => [
                'class' => SiteLayout::className(),
            ],

        ]);

        return $behaviors;

    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['beacon']));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('/site/login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegister()
    {
        if(!\Yii::$app->user->isGuest)
        {
            return $this->redirect(['beacon/list']);
        }

        $model = new PromoForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->role = RbacController::promo_user;
            if ($user = $model->register()) {
                if (Yii::$app->user->login($user)) {
                    return $this->redirect(['beacon/list']);
                }
            }
        }

        return $this->render('promo-register', [
            'model' => $model,
        ]);
    }
}