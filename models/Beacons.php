<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "beacons".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $picture
 * @property string $place
 * @property string $uuid
 * @property integer $minor
 * @property integer $major
 *
 * @property BeaconBindings[] $beaconBindings
 * @property BeaconStatistic $beaconStatistic
 */
class Beacons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['minor', 'major'], 'integer'],
            [['title', 'uuid'], 'string', 'max' => 50],
            [['picture', 'place'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'picture' => Yii::t('app', 'Picture'),
            'place' => Yii::t('app', 'Place'),
            'uuid' => Yii::t('app', 'Uuid'),
            'minor' => Yii::t('app', 'Minor'),
            'major' => Yii::t('app', 'Major'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings()
    {
        return $this->hasMany(BeaconBindings::className(), ['beacon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconStatistic()
    {
        return $this->hasOne(BeaconStatistic::className(), ['id' => 'id']);
    }
}
