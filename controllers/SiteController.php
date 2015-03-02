<?php

namespace app\controllers;

use app\components\SiteLayout;
use Yii;
use yii\filters\AccessControl;
use app\models\Users;
use app\models\ContactForm;

class SiteController extends MainController
{
    public function init()
    {
        $this->activeMap = [
            'login' => [SiteLayout::login => true]  ,
            'register' => [SiteLayout::register => true]  ,
        ];
    }

    public function behaviors()
    {
         return array_merge(parent::behaviors(), [
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
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Users();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegister()
    {
        if(!\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new Users(['scenario'=>'register']);

        if($model->load(Yii::$app->request->post()) && $model->save() &&  $model->login())
        {
            return $this->goHome();
        }
        else
        {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
