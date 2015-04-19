<?php
namespace app\controllers;
use app\filters\TranslationLayout;
use app\models\MessageSearch;
use app\models\SourceMessage;
use app\models\TranslationForm;
use Yii;
use app\components\Alert;

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

    public function actionList()
    {
        $search = new MessageSearch();
        $translationForm = new TranslationForm();
        $translationForm->language = Yii::$app->request->getQueryParam('language', Yii::$app->language);
        $search->load(Yii::$app->request->get());
        $search->language =  $translationForm->language;
        $data_provider = $search->search();
        return $this->render('translation-list',['data_provider'=>$data_provider,'search_model'=>$search,'translationForm'=>$translationForm]);
    }

    public function actionCreate()
    {
        $create = new TranslationForm();
        $create->load(Yii::$app->request->post());
        $create->createMessage();
        return $this->redirect(['translation/list','language'=>$create->language]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel(SourceMessage::className(),$id);
        if($model->delete())
        {
            Alert::addSuccess(Yii::t('messages','Translation has been successfully deleted'));

        }
        else
            Alert::addError(Yii::t('messages','Translation has not been deleted'));
        return $this->redirect(Yii::$app->request->referrer);
    }
}