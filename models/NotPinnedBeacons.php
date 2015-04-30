<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "not_pinned_beacons".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $picture
 * @property string $place
 * @property string $uuid
 * @property integer $minor
 * @property integer $major
 * @property string $alias
 */
class NotPinnedBeacons extends MainActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'not_pinned_beacons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'minor', 'major'], 'integer'],
            [['name', 'uuid', 'minor', 'major'], 'required'],
            [['description'], 'string'],
            [['name', 'picture', 'alias'], 'string', 'max' => 64],
            [['title', 'uuid'], 'string', 'max' => 50],
            [['place'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'picture' => Yii::t('app', 'Picture'),
            'place' => Yii::t('app', 'Place'),
            'uuid' => Yii::t('app', 'Uuid'),
            'minor' => Yii::t('app', 'Minor'),
            'major' => Yii::t('app', 'Major'),
            'alias' => Yii::t('app', 'Alias'),
        ];
    }
}
