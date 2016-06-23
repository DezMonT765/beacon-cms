<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "beacon_pins".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $canvas_width
 * @property integer $canvas_height
 * @property GroupFiles $groupFile
 *
 * @property Beacons $beacon
 */
class BeaconPins extends ActiveRecord
{

    public function getUrl()
    {
        if($this->beacon instanceof Beacons)
        {
            return Url::to(['beacon/view','id'=>$this->beacon->id]);
        }
        else return Yii::$app->homeUrl;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon_pins';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','name'], 'required'],
            [['id', 'x', 'y', 'canvas_width', 'canvas_height'], 'integer'],
            ['url','safe']
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'x' => Yii::t('app', 'X'),
            'y' => Yii::t('app', 'Y'),
            'canvas_width' => Yii::t('app', 'Canvas Width'),
            'canvas_height' => Yii::t('app', 'Canvas Height'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacon()
    {
        return $this->hasOne(Beacons::className(), ['id' => 'id']);
    }

    public function getGroupFile() {
        return $this->hasOne(GroupFiles::className(), ['id' => 'group_file_id']);
    }
}
