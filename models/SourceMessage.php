<?php

namespace app\models;

use app\components\Alert;
use app\components\xlsImport;
use Yii;
use yii\i18n\DbMessageSource;

/**
 * This is the model class for table "source_message".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends MainActiveRecord
{
    public $translation;
    public $language;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $messageSource = Yii::$app->i18n->getMessageSource('*');
        if($messageSource instanceof DbMessageSource)
        {
            return $messageSource->sourceMessageTable;
        }
        else return 'source_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
            [['category','translation'],'required','on'=>xlsImport::XLS_IMPORT],
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        self::initLocalTransaction();
        return true;
    }

    public function afterSave($insert)
    {
        if($insert)
        {
            $message = new Message();
            $message->id = $this->id;
            $message->language = $this->language;
            $message->translation = $this->translation;
            if($message->save())
            {
                Alert::addSuccess(Yii::t('messages','Translation has been saved'));
                self::commitLocalTransaction();
                return true;

            }
            else Alert::addError(Yii::t('messages','Translation has not been saved'),$message->errors);
        }
        self::rollbackLocalTransaction();
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }

    public function search()
    {

    }
}
