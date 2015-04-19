<?php
namespace app\models;
use app\components\FilePathBehavior;
use app\components\KReader;
use app\components\xlsImport;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:21
 *
 */

class TranslationLoad extends Model
{

    public $language;
    public $file;
    public function rules()
    {
        return [
          [['file','language'],'required'],
          ['file','file']
        ];
    }

    public function behaviors()
    {
        return [
           'filePath' => [
               'class' => FilePathBehavior::className(),
               'file_attribute' => 'file'
           ]
        ];
    }

    public function loadTranslation()
    {
        if(!$this->validate())
        {
            return false;
        }

        $xlsImport = new xlsImport(Yii::$app->controller, Yii::$app->request->referrer,SourceMessage::className(), KReader::className());
        $xlsImport->additionalAttributes(['language'=>$this->language]);
        $xlsImport->run();


        return true;
    }

    public function getFileSavePath()
    {
        return self::getFileSaveDir() .  DIRECTORY_SEPARATOR;
    }

    public function getFileViewPath()
    {
        return self::getFileViewUrl() .  '/';
    }




}