<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 25.03.2015
 * Time: 16:53
 */
namespace app\actions;

use app\components\FileSaveBehavior;
use app\helpers\HelperImage;
use Yii;
use yii\base\Action;
use yii\base\Model;

class SaveEditorImage extends  Action
{
    public $model_class;
    public function run()
    {
       /** @var Model | FileSaveBehavior $model */
       $model = new $this->model_class();
       $model->attachBehavior('file-save',[
           'class'=>FileSaveBehavior::className(),
       ]);
        $model->addFileAttribute('picture','@beacon_save_dir','@beacon_view_dir','@backend_beacon_view_dir','@frontend_beacon_view_dir','@beacon_view_url',function ($attribute,$file_path)
        {
            HelperImage::resizeByBound($file_path, $file_path, 400);
        });
        $model->saveFiles();
        echo json_encode(['filelink'=>$model->getFile('picture')]);
    }
}