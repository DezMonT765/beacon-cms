<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $uuid
 * @property int $major
 * @property int $minor
 * @property string $place
 * @property string $description
 *
 * @property BeaconBindings[] $beaconBindings
 */
class Groups extends ActiveRecord
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
            [['name','alias'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['alias'], 'string', 'max' => 64],
            [['alias'], 'unique' ],
            ['uuid', 'string','max'=>64],
            [['major','minor'],'integer'],
            ['place','string','max'=>256],
            ['description','safe']
        ];
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

    public function searchByAttribute($attribute,$value)
    {
        $query = self::find();
        $query->filterWhere(['like',$attribute, $value]);
        return $query->all();
    }

    public function searchByIds(array $ids)
    {
        $query = self::find();
        $query->filterWhere(['id'=>$ids]);
        return $query->all();
    }
}
