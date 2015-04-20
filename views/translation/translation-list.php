<?php

use app\models\TranslationForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Translations');
?>
<div class="user-index">
    <div class="mrg-10">
    <span><?= Yii::t('messages','Translation language:')?>&nbsp;</span>
    <?= \lajax\languagepicker\widgets\LanguagePicker::widget([
                                                                 'skin' => \lajax\languagepicker\widgets\LanguagePicker::SKIN_DROPDOWN,
                                                                 'size' => \lajax\languagepicker\widgets\LanguagePicker::SIZE_SMALL,
                                                                 'link' => function($language){
                                                                     return Url::to(['','language'=>$language]);
                                                                 },
                                                                 'active' => $translationForm->language,
                                                             ]); ?>
    </div>
    <?= $this->render('translation-form',['model'=>$translationForm]) ;?>
    <div class="mrg-10">
        <span><?= Yii::t('messages','Upload translation:')?>&nbsp;</span>
        <?= $this->render('translation-load',['model' => $translationLoad])?>
    </div>
    <?= GridView::widget([
                             'dataProvider' => $data_provider,
                             'filterModel' => $search_model,
                             'columns' => [
                                 ['class' => 'yii\grid\SerialColumn'],
                                 [
                                         'class'=>\dosamigos\grid\EditableColumn::className(),
                                         'format'=>'raw',
                                         'attribute'=>'category',
                                         'url'=>['ajax-update','language'=>$translationForm->language],
                                 ],
                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'attribute'=>'message',
                                     'format'=>'raw',
                                     'url'=>['ajax-update','language'=>$translationForm->language],
                                 ],

                                 [
                                     'class'=>\dosamigos\grid\EditableColumn::className(),
                                     'attribute'=>'messageTranslation',
                                     'format'=>'raw',
                                     'url'=>['ajax-update','language'=>$translationForm->language],
                                 ],
                                 ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{delete}'
                                 ],
                             ],
                         ]); ?>

</div>



