<?php
namespace app\models;
use app\components\Alert;
use app\components\FileSaveBehavior;
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
    public $isUpdate;
    public function rules()
    {
        return [
          [['language','isUpdate'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'isUpdate' => Yii::t('translation_load',':is_update'),
          'file' => Yii::t('translation_load',':file'),
        ];
    }



    public function loadTranslation()
    {
        if(!$this->validate())
        {
            Alert::addError('Translation has not been loaded',$this->errors);
            return false;
        }

        $xlsImport = new xlsImport(Yii::$app->controller, Yii::$app->request->referrer,SourceMessage::className(), KReader::className(),$this,'file',$this->isUpdate);
        $xlsImport->ignoreAttributes(['id']);
        $xlsImport->run();

        return true;
    }





}