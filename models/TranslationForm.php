<?php
namespace app\models;
use app\components\Alert;
use app\models\Message;
use app\models\SourceMessage;
use Yii;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 16.04.2015
 * Time: 12:53
 */

class TranslationForm extends \yii\base\Model
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
        $sourceMessage->category = $this->category;
        $sourceMessage->message = $this->source_message;
        $transaction = Yii::$app->db->beginTransaction();
        if($sourceMessage->save())
        {
            $message = new Message();
            $message->id = $sourceMessage->id;
            $message->language = $this->language;
            $message->translation = $this->translation;
            if($message->save())
            {
                Alert::addSuccess(Yii::t('messages','Translation has been saved'));
                $transaction->commit();
                return true;

            }
            else Alert::addError(Yii::t('messages','Translation has not been saved'),$message->errors);
        }
        else Alert::addError(Yii::t('messages','Translation has not been saved'),$sourceMessage->errors);
        $transaction->rollBack();
        return false;
    }


}