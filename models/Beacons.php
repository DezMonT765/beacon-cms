<?php

namespace app\models;

use app\components\Crop;
use app\components\FileSaveBehavior;
use app\helpers\HelperImage;
use Yii;
use yii\web\Request;
use yii\web\UploadedFile;

/**
 * This is the model class for table "beacons".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $description
 * @property string|UploadedFile $picture
 * @property string $place
 * @property string $uuid
 * @property integer $minor
 * @property integer $major
 * @property string $groupToBind
 *
 * @property BeaconBindings[] $beaconBindings
 * @property BeaconStatistic $beaconStatistic
 */
class Beacons extends MainActiveRecord
{

    public $crop;
    public function init() {
        /**@var Beacons | FileSaveBehavior $this*/
        $crop = $this->crop;
        $this->addFileAttribute('picture','@beacon_save_dir','@beacon_view_dir','@backend_beacon_view_dir','@frontend_beacon_view_dir','@beacon_view_url',function ($attribute,$file_path) use ($crop) {
            HelperImage::imgCropByScale(
                $file_path,
                $file_path,
                Crop::getAttribute($attribute,Crop::X1),
                Crop::getAttribute($attribute,Crop::Y1),
                Crop::getAttribute($attribute,Crop::WIDTH),
                Crop::getAttribute($attribute,Crop::HEIGHT),
                Crop::getAttribute($attribute,Crop::SCALE)
            );
        });

        $this->addFileAttribute('horizontal_picture','@beacon_save_dir','@beacon_view_dir','@backend_beacon_view_dir','@frontend_beacon_view_dir','@beacon_view_url',function ($attribute,$file_path) use ($crop) {
            HelperImage::imgCropByScale(
                $file_path,
                $file_path,
                Crop::getAttribute($attribute,Crop::X1),
                Crop::getAttribute($attribute,Crop::Y1),
                Crop::getAttribute($attribute,Crop::WIDTH),
                Crop::getAttribute($attribute,Crop::HEIGHT),
                Crop::getAttribute($attribute,Crop::SCALE)
            );
        });
    }

    public function behaviors() {
        return [
            FileSaveBehavior::className(),
        ];
    }

    public function getGroupsName() {
        $groups =  $this->groups;
        $names = [];
        foreach($groups as $group) {
            $names[] = $group->name;
        }
        return implode(',',$names);
    }

    public $absolutePicture;
    public $absoluteHorizontalPicture;
    public $pictureFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacons';
    }

    public function setGroupToBind($group)
    {
        $this->groupToBind = $group;
    }

    public function getGroupToBind()
    {
        if($this->groupToBind !== null)
        {
           return $this->groupToBind;
        }
        else
        {
            $group = $this->getGroups()->one();
            if($group instanceof Groups)
            {
                $result = $group->id;
                return $result;
            }
            else {
                $result = '';
                return $result;
            }

        }
    }

    public function getGroupId() {
        return $this->groupToBind;
    }

    public function getGroupName() {
        if($this->groupToBind !== null)
        {
            return $this->groupToBind;
        }
        else {
            $group = $this->getGroups()->one();
            if($group instanceof Groups)
            {
                $result = $group->name;
                return $result;
            }
            else {
                $result = '';
                return $result;
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','unique'],
            [['name','title','description','uuid','minor','major','place'],'required'],
            [['description'], 'string'],
            [['minor', 'major'], 'integer'],
            [['title', 'uuid'], 'string', 'max' => 50],
            [['pictureFile'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
            ['link','url'],
            [['groupToBind','absolutePicture','groupName','groupId','absoluteHorizontalPicture','link','additional_info'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('beacon', ':name'),
            'groupToBind' => Yii::t('beacon', ':group'),
            'title' => Yii::t('beacon', ':title'),
            'description' => Yii::t('beacon', ':description'),
            'picture' => Yii::t('beacon', ':picture'),
            'place' => Yii::t('beacon', ':place'),
            'uuid' => Yii::t('beacon', ':uuid'),
            'minor' =>Yii::t('beacon', ':minor'),
            'major' => Yii::t('beacon', ':major'),
        ];
    }


    public function saveGroup()
    {
        if(!empty($this->groupToBind))
        {
            BeaconBindings::deleteAll(['beacon_id' => $this->id]);
            $group = Groups::findOne(['id' => $this->groupToBind]);
            if($group instanceof Groups)
            {
                $beacon_binding = new BeaconBindings();
                $beacon_binding->beacon_id = $this->id;
                $beacon_binding->group_id = $group->id;
                $beacon_binding->save();
            }
        }
    }

    public function afterSave($insert,$changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        self::saveGroup();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings()
    {
        return $this->hasMany(BeaconBindings::className(), ['beacon_id' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Groups::className(),['id'=>'group_id'])
            ->via('beaconBindings');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconStatistic()
    {
        return $this->hasOne(BeaconStatistic::className(), ['id' => 'id']);
    }


    public function getClientBeacons() {
        return $this->hasMany(ClientBeacons::className(), ['beacon_id' => 'id']);
    }

    public function getClientUsers() {
        return $this->hasMany(ClientUsers::className(), ['id' => 'client_id'])
            ->via('clientBindings');
    }

    public function afterFind()
    {
        if(Yii::$app->request instanceof Request) {
            $this->absolutePicture = Yii::$app->request->getHostInfo() . $this->getFile('picture');
            $this->absoluteHorizontalPicture =  Yii::$app->request->getHostInfo() . $this->getFile('horizontal_picture');
        }
    }

    public function fields(){
        $fields = parent::fields();
        $fields['absolutePicture'] = 'absolutePicture';
        $fields['groupToBind'] = 'groupToBind';
        return $fields;
    }
    
    public function getBeaconPins() {
        return $this->hasOne(BeaconPins::className(),['id'=>'id']);
    }
}
