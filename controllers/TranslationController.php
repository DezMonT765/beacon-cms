<?php
namespace app\controllers;
use app\actions\TranslationEditableAction;
use app\actions\UserEditableAction;
use app\filters\TranslationLayout;
use app\models\Message;
use app\models\MessageSearch;
use app\models\SourceMessage;
use app\models\SourceMessageSearch;
use app\models\TranslationForm;
use app\models\TranslationLoad;
use Yii;
use app\components\Alert;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 15.04.2015
 * Time: 14:30
 */

class TranslationController extends MainController
{
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//            ]
            'layout' => ['class'=>TranslationLayout::className()]
        ];
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => TranslationEditableAction::className(),
                'modelClass' => SourceMessage::className(),
                'forceCreate' => false,
                'preProcess'=>function($model){
                    $model->language = Yii::$app->request->get('language');
                }
            ]
        ];
    }

    public function actionLoad()
    {
        $translationLoad = new TranslationLoad();
        $translationLoad->load(Yii::$app->request->post());
        $translationLoad->loadTranslation();
        return $this->redirect(['translation/import','language'=>$translationLoad->language]);
    }

    public function actionList()
    {
        $search = new SourceMessageSearch();
        $translationForm = new TranslationForm();

        $translationForm->language = Yii::$app->request->getQueryParam('language', Yii::$app->language);

        $search->load(Yii::$app->request->get());
        $search->language =  $translationForm->language;
        $data_provider = $search->search();
        return $this->render('translation-list',['data_provider'=>$data_provider,'search_model'=>$search,
                                                 'translationForm'=>$translationForm,
        ]);
    }

    public function actionCreate()
    {
        $create = new TranslationForm();
        $create->load(Yii::$app->request->post());
        $create->createMessage();
        return $this->redirect(['translation/list','language'=>$create->language]);
    }

    public function actionDelete($id,$language)
    {
        $model = $this->findModel(Message::className(),['id'=>$id,'language'=>$language]);
        if($model->delete())
        {
            Alert::addSuccess(Yii::t('messages','Translation has been successfully deleted'));

        }
        else
            Alert::addError(Yii::t('messages','Translation has not been deleted'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMassDelete($id)
    {

    }

    public function actionImport()
    {
        $translationLoad = new TranslationLoad();
        $translationLoad->language = Yii::$app->request->getQueryParam('language', Yii::$app->language);
        return $this->render('translation-load',['model'=>$translationLoad]);
    }
}