<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_dynamic_maps".
 *
 * @property integer $id
 * @property resource $data
 * @property resource $map
 *
 * @property GroupFiles $groupFile
 */
class BeaconMaps extends MainActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon_maps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupFiles::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            if(empty($this->data)) {
                $this->data = json_encode([]);
            }
            return true;
        }
        else return false;
    }

    public function getMap() {
        if(empty($this->data)) {
            return [];
        }
        else return unserialize($this->data);
    }

    public function setMap($data) {
        if(empty($data)) {
            $this->data = [];
        }
        else {
            $this->data = $data;
        }
        $this->data = serialize($this->data);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupFile()
    {
        return $this->hasOne(GroupFiles::className(), ['id' => 'id']);
    }
}
