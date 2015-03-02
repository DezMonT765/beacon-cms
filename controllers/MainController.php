<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 28.02.2015
 * Time: 20:03
 */

namespace app\controllers;
use yii\filters\AccessControl;
use yii\web\Controller;

class MainController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
            ]
        ];
    }
    public $activeMap;

    public function getTabsActivity()
    {
        return isset($this->activeMap[$this->action->id]) ? $this->activeMap[$this->action->id] : [];
    }
}