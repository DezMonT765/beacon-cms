<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistics".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string clientEmail
 * @property integer $client_id
 */
class Info extends MainActiveRecord
{

    public function getClientEmail(){
        if(!empty($this->clientEmail))
            return $this->clientEmail;
        else
        {
            $client_email = !empty($this->client) ? $this->client->email : null;
            return $client_email;
        }
    }

    public function setClientEmail($email) {
        $this->clientEmail = $email;
    }

    public function getClient()  {
        return $this->hasOne(ClientUsers::className(),['id'=>'client_id']);
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id'], 'integer'],
            [['client_id','key','value'], 'required'],
            [['key', 'value'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }
}
