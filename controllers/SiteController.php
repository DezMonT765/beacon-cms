<?php

namespace app\controllers;

use app\components\Alert;
use app\filters\SiteLayout;
use app\models\LoginForm;
use app\models\PasswordResetForm;
use app\models\RegisterForm;
use app\models\PasswordChangeForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use app\models\ContactForm;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

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
        if(!Yii::$app->user->isGuest)
           return $this->redirect(['user/list']);
        $this->layout = 'landing-base-layout';
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


    public function actionPasswordReset()
    {
        $model = new PasswordResetForm();
        if(Yii::$app->user->isGuest)
        {
            if($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->sendEmail();
                Alert::addSuccess('Thank you. If the email address you entered matches with one that is registered in our system we will send you a reset link within the next few minutes.');
                return $this->goHome();
            }
            else
            {
                return $this->render('password-reset', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            $model->email = Yii::$app->user->identity->email;
        }
        if($model->validate())
        {
            $model->sendEmail();
            Alert::addSuccess('Thank you. If the email address you entered matches with one that is registered in our system we will send you a reset link within the next few minutes.');
            return $this->goHome();
        }
        else
        {
            return $this->render('password-reset', [
                'model' => $model,
            ]);
        }
    }





    public function actionPasswordChange($token)
    {
        try
        {
            $model = new PasswordChangeForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException('Wrong password reset token.');
        }
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate() && $model->changePassword())
            {
                Alert::addSuccess('New password was saved.');
                return $this->goHome();
            }
            else
            {
                Alert::addError('Password hasn\'t been saved.');
                return $this->goHome();
            }
        }
        return $this->render('password-change', [
            'model' => $model,
        ]);
    }

    public function actionTerms() {
        return $this->render('terms');
    }





}
