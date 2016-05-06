<?php
namespace app\filters;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 11.05.2015
 * Time: 12:38
 */

class KeyFilter extends ActionFilter
{
    public $key;
    public function beforeAction($action)
    {
        $key = Yii::$app->request->getQueryParam('key');
        if(!empty($key) && $key === $this->key)
            return true;
        else throw new ForbiddenHttpException('Access denied');
    }
}