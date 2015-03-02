<?php

namespace app\models;

use Yii;
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
class Beacons extends \yii\db\ActiveRecord
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
                if(is_file($this->getImageSavePath(). $this->oldAttributes['picture']))
                {
                    unlink($this->getImageSavePath(). $this->oldAttributes['picture']);
                }
                $this->picture = $this->getImageName() . $this->pictureFile->extension;
            }
            else
            {
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
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings()
    {
        return $this->hasMany(BeaconBindings::className(), ['beacon_id' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(Users::className(),['id'=>'user_id'])
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
