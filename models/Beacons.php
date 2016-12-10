<?php
namespace app\models;

use app\components\Crop;
use app\components\FileSaveBehavior;
use app\helpers\HelperImage;
use dezmont765\yii2bundle\behaviors\ModelBindingBehavior;
use dezmont765\yii2bundle\models\MainActiveRecord;
use Yii;
use yii\helpers\Url;
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
 * @property string $tagsToBind
 * @property BeaconPins $beaconPins
 * @mixin ModelBindingBehavior
 * @mixin FileSaveBehavior
 *
 * @property BeaconBindings[] $beaconBindings
 * @property BeaconStatistic $beaconStatistic
 */
class Beacons extends MainActiveRecord
{
    public $crop;


    public function init() {
        $crop = $this->crop;
        $this->addFileAttribute('picture', '@beacon_save_dir', '@beacon_view_dir', null,
                                null, '@beacon_view_url',
            function ($attribute, $file_path) use ($crop) {
                HelperImage::imgCropByScale(
                    $file_path,
                    $file_path,
                    Crop::getAttribute($attribute, Crop::X1),
                    Crop::getAttribute($attribute, Crop::Y1),
                    Crop::getAttribute($attribute, Crop::WIDTH),
                    Crop::getAttribute($attribute, Crop::HEIGHT),
                    Crop::getAttribute($attribute, Crop::SCALE)
                );
            });
        $this->addFileAttribute('horizontal_picture', '@beacon_save_dir', '@beacon_view_dir',
                                null, null, '@beacon_view_url',
            function ($attribute, $file_path) use ($crop) {
                HelperImage::imgCropByScale(
                    $file_path,
                    $file_path,
                    Crop::getAttribute($attribute, Crop::X1),
                    Crop::getAttribute($attribute, Crop::Y1),
                    Crop::getAttribute($attribute, Crop::WIDTH),
                    Crop::getAttribute($attribute, Crop::HEIGHT),
                    Crop::getAttribute($attribute, Crop::SCALE)
                );
            });
    }


    public function beforeValidate() {
        if(parent::beforeValidate()) {
            do {
                $query = self::find()->where(['uuid' => $this->uuid, 'minor' => $this->minor, 'major' => $this->major]);
                if($this->isNewRecord) {
                }
                if(!$this->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->id]]);
                }
                $beacons = $query->one();
                if($beacons instanceof Beacons) {
                    $this->major += 1;
                }
            }
            while($beacons instanceof Beacons);
            return true;
        }
        else return false;
    }


    public function behaviors() {
        return [
            FileSaveBehavior::className(),
            [
                'class' => ModelBindingBehavior::className(),
                ModelBindingBehavior::BINDING_STORES => [
                    'groupToBind' => [
                        ModelBindingBehavior::BINDING_STORE_ATTRIBUTE => '_groupToBind',
                        ModelBindingBehavior::BINDING_MODEL_QUERY => $this->getGroups(),
                        ModelBindingBehavior::BINDING_MODEL_CLASS => Groups::className(),
                        ModelBindingBehavior::BINDING_ATTRIBUTE => 'id',
                        ModelBindingBehavior::BINDING_INTERMEDIATE_MODEL_CLASS => BeaconBindings::className(),
                        ModelBindingBehavior::BINDING_INTERMEDIATE_MODEL_ATTRIBUTE => 'beacon_id',
                        ModelBindingBehavior::BINDING_INTERMEDIATE_RELATED_ATTRIBUTE => 'group_id',
                    ],
                    'tagsToBind' => [
                        ModelBindingBehavior::BINDING_STORE_ATTRIBUTE => '_tagsToBind',
                        ModelBindingBehavior::BINDING_MODEL_QUERY => $this->getTags(),
                        ModelBindingBehavior::BINDING_MODEL_CLASS => Tags::className(),
                        ModelBindingBehavior::BINDING_ATTRIBUTE => 'id',
                        ModelBindingBehavior::BINDING_ATTRIBUTE_DELIMITER => ',',
                        ModelBindingBehavior::BINDING_INTERMEDIATE_MODEL_CLASS => BeaconTags::className(),
                        ModelBindingBehavior::BINDING_INTERMEDIATE_MODEL_ATTRIBUTE => 'beacon_id',
                        ModelBindingBehavior::BINDING_INTERMEDIATE_RELATED_ATTRIBUTE => 'tag_id',
                    ],
                ],
            ]
        ];
    }


    public $absolutePicture;
    public $absoluteHorizontalPicture;
    public $pictureFile;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'beacons';
    }


    public $_groupToBind = null;


    public function setGroupToBind($group) {
        $this->_groupToBind = $group;
    }


    public function getGroupToBind() {
        return $this->getSingleBinding('groupToBind');
    }


    public $_tagsToBind = null;

    // todo it is so easy to forget about it, need to resolve this somehow
    public function setTagsToBind($tags) {
        $this->_tagsToBind = explode(',', $tags);
    }


    public function getTagsToBind() {
        return $this->getMultipleBinding('tagsToBind');
    }


    public function getGroupId() {
        return $this->groupToBind;
    }


    public $_groupName = null;


    public function setGroupName($groupName) {
        $this->_groupName = $groupName;
    }


    public function getGroupName() {
        $group = $this->getGroups()->one();
        if($group instanceof Groups) {
            return $group->name;
        }
        else return null;
    }


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['name', 'unique'],
            [['name', 'title', 'description', 'uuid', 'minor', 'major', 'place'], 'required'],
            [['description'], 'string'],
            [['minor', 'major'], 'integer'],
            [['title', 'uuid'], 'string', 'max' => 50],
            [['pictureFile'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
            ['link', 'url'],
            [['groupToBind', 'tagsToBind', 'absolutePicture', 'groupName', 'groupId', 'absoluteHorizontalPicture',
              'link',
              'additional_info', 'absoluteMapFolderUrl', 'mapWidth', 'mapHeight', 'x', 'y'], 'safe']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('beacon', ':name'),
            'groupToBind' => Yii::t('beacon', ':group'),
            'title' => Yii::t('beacon', ':title'),
            'description' => Yii::t('beacon', ':description'),
            'picture' => Yii::t('beacon', ':picture'),
            'place' => Yii::t('beacon', ':place'),
            'uuid' => Yii::t('beacon', ':uuid'),
            'minor' => Yii::t('beacon', ':minor'),
            'major' => Yii::t('beacon', ':major'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings() {
        return $this->hasMany(BeaconBindings::className(), ['beacon_id' => 'id']);
    }


    public function getGroups() {
        return $this->hasMany(Groups::className(), ['id' => 'group_id'])
                    ->via('beaconBindings');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconStatistic() {
        return $this->hasOne(BeaconStatistic::className(), ['id' => 'id']);
    }


    public function getClientBeacons() {
        return $this->hasMany(ClientBeacons::className(), ['beacon_id' => 'id']);
    }


    public function getClientUsers() {
        return $this->hasMany(ClientUsers::className(), ['id' => 'client_id'])
                    ->via('clientBindings');
    }


    public function afterFind() {
        if(Yii::$app->request instanceof Request) {
            $this->absolutePicture = Yii::$app->request->getHostInfo() . $this->getFile('picture');
            $this->absoluteHorizontalPicture = Yii::$app->request->getHostInfo() . $this->getFile('horizontal_picture');
        }
    }


    public function fields() {
        $fields = parent::fields();
        $fields['absolutePicture'] = 'absolutePicture';
        $fields['groupToBind'] = 'groupToBind';
        $fields['tagsToBind'] = 'tagsToBind';
        $fields['absoluteHorizontalPicture'] = 'absoluteHorizontalPicture';
        $fields['groupName'] = 'groupName';
        $fields['groupId'] = 'groupId';
        $fields['absoluteMapUrl'] = 'absoluteMapUrl';
        $fields['mapWidth'] = 'mapWidth';
        $fields['mapHeight'] = 'mapHeight';
        $fields['x'] = 'x';
        $fields['y'] = 'y';
        $fields['content'] = 'content';
        return $fields;
    }


    public function getBeaconPins() {
        return $this->hasOne(BeaconPins::className(), ['id' => 'id']);
    }


    public function getX() {
        if($this->beaconPins instanceof BeaconPins) {
            $x = $this->beaconPins->x;
            return $x;
        }
        else return 0;
    }


    public function getY() {
        if($this->beaconPins instanceof BeaconPins) {
            $y = $this->beaconPins->y;
            return $y;
        }
        else return 0;
    }


    public function getAbsoluteMapFolderUrl() {
        /**@var Groups | FileSaveBehavior $group */
        if($this->beaconPins instanceof BeaconPins && $this->beaconPins->groupFile instanceof GroupFiles) {
            $file_name = $this->beaconPins->groupFile->name;
            $group = $this->beaconPins->groupFile->group;
            if($group instanceof Groups) {
                $dir = pathinfo($group->getFileByName($file_name, 'map'), PATHINFO_FILENAME);
                $url = Url::to([$group->getFileViewPath('map') . $dir], true);
                return $url;
            }
        }
        return "";
    }


    public function getAbsoluteMapUrl() {
        if($this->beaconPins instanceof BeaconPins && $this->beaconPins->groupFile instanceof GroupFiles) {
            $file_name = $this->beaconPins->groupFile->name;
            $group = $this->beaconPins->groupFile->group;
            if($group instanceof Groups) {
                $url = Url::to([$group->getFileByName($file_name, 'map')], true);
                return $url;
            }
        }
        return "";
    }


    public function getMapWidth() {
        /**@var Groups | FileSaveBehavior $group */
        if($this->beaconPins instanceof BeaconPins && $this->beaconPins->groupFile instanceof GroupFiles) {
            $file_name = $this->beaconPins->groupFile->name;
            $group = $this->beaconPins->groupFile->group;
            if($group instanceof Groups) {
                $group->getFileSavePath('map');
                $info = getimagesize($group->getFileSavePath('map') . $file_name);
                if(isset($info[0])) {
                    return $info[0];
                }
            }
        }
        return 0;
    }


    public function getContent() {
        $beacons = BeaconContentElements::find()->where(['beacon_id' => $this->id])->all();
        $content = [];
        foreach($beacons as $beacon) {
            $content[] = $beacon->toArray();
        }
        return $beacons;
    }


    public function getMapHeight() {
        /**@var Groups | FileSaveBehavior $group */
        if($this->beaconPins instanceof BeaconPins && $this->beaconPins->groupFile instanceof GroupFiles) {
            $file_name = $this->beaconPins->groupFile->name;
            $group = $this->beaconPins->groupFile->group;
            if($group instanceof Groups) {
                $group->getFileSavePath('map');
                $info = getimagesize($group->getFileSavePath('map') . $file_name);
                if(isset($info[1])) {
                    return $info[1];
                }
            }
        }
        return 0;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconTags() {
        return $this->hasMany(BeaconTags::className(), ['beacon_id' => 'id']);
    }


    private function getTags() {
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
                    ->via('beaconTags');
    }
}
