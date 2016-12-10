<?php
namespace app\models;

use app\behaviors\AliasBehavior;
use app\components\FileSaveBehavior;
use app\components\UUID;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

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
 * @mixin  FileSaveBehavior
 *
 * @property BeaconBindings[] $beaconBindings
 * @property Beacons $beacons
 */
class Groups extends MainActiveRecord
{

    public $map = null;
    public static function getDropdownList() {
        $result = [];
        $models = self::find()->all();
        foreach($models as $model) {
            $result[$model->id] = $model->name;
        }
        return $result;
    }


    public function init() {
        $this->addFilesAttribute('map',GroupFiles::className(),'name',GroupFiles::TYPE_DEFAULT,'owner_id','type',
                                 '@group_save_dir','@group_view_url',null,null,'@group_view_url',function($attribute,$file_full_path,$file_path,$file_name) {
                FileHelper::createDirectory($file_path . $file_name);
                exec("convert ". $file_full_path . " -crop 256x256 -set filename:tile \"%[fx:page.x/256]-%[fx:page.y/256]\"  +repage +adjoin ".$file_path . $file_name. DIRECTORY_SEPARATOR . "tile-%[filename:tile].png");
            });

    }
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'groups';
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes); 
        $this->updateBeacons();
    }
    
    public function updateBeacons() {
        $beacons = $this->beacons;
        foreach($beacons as $beacon) {
            $beacon->uuid = $this->uuid;
            $beacon->save();
        } 
    } 


    public function behaviors() {
        return [
            'slug' => [
                'class' => AliasBehavior::className(),
                'in_attribute' => 'name',
                'out_attribute' => 'alias',
                'translit' => true
            ],
            'class' => FileSaveBehavior::className(),
        ];
    }

    public $is_force_uuid = false;


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['name', 'alias'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['alias'], 'string', 'max' => 64],
            [['alias'], 'unique'],
            ['uuid', 'string', 'max' => 64],
            [['major', 'minor'], 'integer'],
            ['place', 'string', 'max' => 256],
            [['description','map'], 'safe']
        ];
    }

    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            if(empty($this->uuid) || $this->is_force_uuid)
                $this->uuid = UUID::v4();
            return true;
        }
        else return false;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'alias' => Yii::t('group', ':alias'),
            'name' => Yii::t('group', ':name'),
            'description' => Yii::t('group', ':description'),
            'uuid' => Yii::t('group', ':uuid'),
            'major' => Yii::t('group', ':major'),
            'minor' => Yii::t('group', ':minor'),
            'place' => Yii::t('group', ':place'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings() {
        return $this->hasMany(BeaconBindings::className(), ['group_id' => 'id']);
    }


    public function getBeacons() {
        return $this->hasMany(Beacons::className(), ['id' => 'beacon_id'])
                    ->via('beaconBindings');
    }


    public function getUserBindings() {
        return $this->hasMany(UserBindings::className(), ['group_id' => 'id']);
    }


    public function getUsers() {
        return $this->hasMany(Users::className(), ['id' => 'user_id'])
                    ->via('userBindings');
    }


}
