<?php
namespace app\models;

use dezmont765\yii2bundle\components\Alert;
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


    public function rules() {
        return [
            [['language', 'category', 'translation', 'source_message'], 'required'],
        ];
    }


    public function attributeLabels() {
        return [
            'category' => Yii::t('translation', ':category'),
            'source_message' => Yii::t('translation', ':message'),
            'translation' => Yii::t('translation', ':translation'),
        ];
    }


    public function createMessage() {
        if(!$this->validate()) {
            Alert::addError(Yii::t('messages', 'Translation has not been saved'), $this->errors);
            return false;
        }
        $sourceMessage = new SourceMessage();
        $sourceMessage->language = $this->language;
        $sourceMessage->category = $this->category;
        $sourceMessage->message = $this->source_message;
        $sourceMessage->messageTranslation = $this->translation;
        if(!$sourceMessage->save()) {
            Alert::addError(Yii::t('messages', 'Translation has not been saved'), $sourceMessage->errors);
        }
        return false;
    }


}