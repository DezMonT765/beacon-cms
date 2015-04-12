<?php

namespace app\controllers;

use app\commands\RbacController;
use app\filters\SiteLayout;
use app\models\LoginForm;
use app\models\RegisterForm;
use Yii;
use yii\filters\AccessControl;
use app\models\Users;
use app\models\ContactForm;
use yii\helpers\Url;

class SiteController extends MainController
{

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
            return $this->redirect(Url::to('/beacon'));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                if (Yii::$app->user->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
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



    public function actionTest()
    {
        var_dump(self::generateRoleCondition(RbacController::$role_hierarchy,RbacController::user));
    }



    protected  function  generateRoleCondition($roles,$checking_role,$condition = false)
    {
        foreach ($roles as $role=>$parents)
        {
//            $condition = $condition || ($role == $checking_role);
            if(is_array($parents))
            {
                $condition = self::generateRoleCondition($parents, $checking_role, $condition);
                $condition = $condition || $role == $checking_role;
            }
            else
            {
                $condition = $condition || ($role == $checking_role);
            }


        }
        return $condition;
    }
}
