<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
?>
<div class="users-index">

    <br>

    <?= GridView::widget([
                             'dataProvider' => $dataProvider,
                             'filterModel' => $searchModel,
                             'columns' => [
                                 [
                                     'class' => \yii\grid\DataColumn::className(),
                                     'attribute' => 'name',
                                     'value' => function($model)
                                     {
                                         return Html::a($model->name,Url::to(['update','id'=>$model->id]));
                                     },
                                     'format' => 'html',
                                     'label' => 'Name',
                                 ],
                                 'email:email',
                                 // 'access_token',

                                 ['class' => 'yii\grid\ActionColumn'],
                             ],
                         ]); ?>

</div>
