<?php
namespace app\filters;
use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.09.2015
 * Time: 12:51
 */


class FilterJson extends ActionFilter {

    public function beforeAction($action) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
}