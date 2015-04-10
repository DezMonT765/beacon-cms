<?php

namespace app\models;

use app\components\Alert;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "beacons".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string|UploadedFile $picture
 * @property string $place
 * @property string $uuid
 * @property integer $minor
 * @property integer $major
 *
 * @property BeaconBindings[] $beaconBindings
 * @property BeaconStatistic $beaconStatistic
 */
class Beacons extends ActiveRecord
{


    public $pictureFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacons';
    }

    public function getImageSaveDir()
    {
        return Yii::$app->params['image_save_dir'];
    }

    public function getImageSavePath()
    {
        return self::getImageSaveDir() . $this->id . DIRECTORY_SEPARATOR;
    }

    public function getImageViewDir()
    {
        return Yii::$app->params['image_view_dir'];
    }

    public function getImageViewPath()
    {
        return self::getImageViewDir() . $this->id . '/';
    }

    public function getImage()
    {
        return self::getImageViewPath() . $this->picture;
    }

    public function getImageName()
    {
        return Yii::$app->security->generateRandomString(16) . '.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','description','uuid','minor','major'],'required'],
            [['description'], 'string'],
            [['minor', 'major'], 'integer'],
            [['title', 'uuid'], 'string', 'max' => 50],
            [['picture'], 'string', 'max' => 64],
            [['pictureFile'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
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
    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            $this->pictureFile = UploadedFile::getInstance($this, 'picture');
            if($this->pictureFile instanceof UploadedFile)
            {
                if(!$this->isNewRecord)
                {

                    if(is_file($this->getImageSavePath(). $this->oldAttributes['picture']))
                    {
                        unlink($this->getImageSavePath(). $this->oldAttributes['picture']);
                    }
                }
                $this->picture = $this->getImageName() . $this->pictureFile->extension;
            }
            else
            {
                if(isset($this->oldAttributes['picture']))
                    $this->picture = $this->oldAttributes['picture'];
            }
            return true;
        }
        else return false;
    }

    public function afterDelete()
    {
        FileHelper::removeDirectory($this->getImageSavePath());
    }

    public function afterSave($insert,$changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if(!is_dir($this->getImageSavePath()))
        {
            FileHelper::createDirectory($this->getImageSavePath());
        }
        if($this->pictureFile instanceof UploadedFile)
            $this->pictureFile->saveAs($this->getImageSavePath() . $this->picture);
        $user = Users::getLogged(true);
        $groups = $user->getGroups()->all();
        foreach ($groups as $group)
        {
            if($group instanceof Groups)
            {
                $beacon_bindings = BeaconBindings::findOne(['beacon_id'=>$this->id,'group_id'=>$group->id]);
                if(!($beacon_bindings instanceof BeaconBindings))
                {
                    $beacon_bindings = new BeaconBindings();
                }
                $beacon_bindings->group_id = $group->id;
                $beacon_bindings->beacon_id = $this->id;
                if($beacon_bindings->save())
                {
                    Alert::addSuccess('Beacon has been succesfully saved');
                }
            }
        }
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
}
