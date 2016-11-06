<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_files".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $name
 * @property string $type
 *
 * @property Groups $group
 * @property BeaconMaps $beaconMap
 */
class GroupFiles extends MainActiveRecord
{
    
    const TYPE_DEFAULT = 'default';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id'], 'integer'],
            [['name', 'type'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'owner_id']);
    }

    public function getBeaconMap() {
        return $this->hasOne(BeaconMaps::className(), ['id'=>'id']);
    }
}
