<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_beacons".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $beacon_id
 */
class ClientBeacons extends \yii\db\ActiveRecord
{
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
}
