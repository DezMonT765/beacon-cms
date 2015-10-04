<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_beacons".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $beacon_id
 * @property string $beaconTitle
 */
class ClientBeacons extends MainActiveRecord
{

    public function setBeaconTitle($email) {
        $this->beaconTitle = $email;
    }

    public function getBeaconTitle() {
        if(!empty($this->beaconTitle))
            return $this->beaconTitle;
        else
        {
            $beaconTitle = !empty($this->beacon) ? $this->beacon->title  : null;
            return $beaconTitle;
        }
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_beacons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id','beacon_id'],'required'],
            [['client_id', 'beacon_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'beacon_id' => Yii::t('app', 'Beacon ID'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(ClientUsers::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacon()
    {
        return $this->hasOne(Beacons::className(), ['id' => 'beacon_id']);
    }

    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->created = date('Y-m-d H:i:s');
            }
            $this->updated = date('Y-m-d H:i:s');
        }
        return true;
    }
}
