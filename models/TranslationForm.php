<?php
namespace app\models;
use app\components\Alert;
use app\models\Message;
use app\models\SourceMessage;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 16.04.2015
 * Time: 12:53
 */

class TranslationForm extends Model
{
    public $language;
    public $category;
    public $translation;
    public $source_message;

    public function rules()
    {
        return [
            [['language','category','translation','source_message'],'required'],
        ];
    }

    public function createMessage()
    {
        if(!$this->validate()){
            Alert::addError(Yii::t('messages','Translation has not been saved'),$this->errors);
            return false;
        }
        $sourceMessage = new SourceMessage();
        $sourceMessage->language = $this->language;
        $sourceMessage->category = $this->category;
        $sourceMessage->message = $this->source_message;
        $sourceMessage->messageTranslation = $this->translation;
        if(!$sourceMessage->save())
             Alert::addError(Yii::t('messages','Translation has not been saved'),$sourceMessage->errors);
        return false;
    }


}