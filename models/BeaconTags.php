<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "beacon_tags".
 *
 * @property integer $id
 * @property integer $beacon_id
 * @property integer $tag_id
 *
 * @property Tags $tag
 * @property Beacons $beacon
 */
class BeaconTags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['beacon_id', 'tag_id'], 'integer'],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['tag_id' => 'id']],
            [['beacon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beacons::className(), 'targetAttribute' => ['beacon_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'beacon_id' => Yii::t('app', 'Beacon ID'),
            'tag_id' => Yii::t('app', 'Tag ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tags::className(), ['id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacon()
    {
        return $this->hasOne(Beacons::className(), ['id' => 'beacon_id']);
    }
}
