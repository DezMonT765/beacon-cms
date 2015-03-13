<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property string $token
 * @property string $name
 *
 * @property BeaconBindings[] $beaconBindings
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['token'], 'string', 'max' => 64],
            [['token'], 'unique' ],
        ];
    }




    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                if($this->scenario != 'search')
                    $this->token = Yii::$app->security->generateRandomString(64);
            }
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'token' => Yii::t('app', 'Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings()
    {
        return $this->hasMany(BeaconBindings::className(), ['group_id' => 'id']);
    }

    public function getBeacons()
    {
        return $this->hasMany(Beacons::className(),['id'=>'beacon_id'])
            ->via('beaconBindings');
    }

    public function getUserBindings()
    {
        return $this->hasMany(UserBindings::className(),['group_id'=>'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(Users::className(),['id'=>'user_id'])
            ->via('userBindings');
    }
}
