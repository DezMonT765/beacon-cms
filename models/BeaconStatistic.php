<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "beacon_statistic".
 *
 * @property integer $id
 * @property integer $power_level
 * @property integer $show_count
 * @property integer $click_count
 *
 * @property Beacons $id0
 */
class BeaconStatistic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon_statistic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'power_level', 'show_count', 'click_count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'power_level' => Yii::t('app', 'Power Level'),
            'show_count' => Yii::t('app', 'Show Count'),
            'click_count' => Yii::t('app', 'Click Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Beacons::className(), ['id' => 'id']);
    }
}
