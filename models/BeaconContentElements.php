<?php

namespace app\models;

use app\components\Crop;
use app\components\FileSaveBehavior;
use app\helpers\HelperImage;
use Yii;
use yii\web\Request;

/**
 * This is the model class for table "beacon_content_elements".
 *
 * @property integer $id
 * @property integer $beacon_id
 * @property string $title
 * @property string $link
 * @property string $description
 * @property string $picture
 * @property string $horizontal_picture
 * @property string $additional_info
 *
 * @property Beacons $beacon
 */
class BeaconContentElements extends MainActiveRecord
{
    public function init() {
        /**@var Beacons | FileSaveBehavior $this */
        $crop = $this->crop;
        $this->addFileAttribute('picture', '@beacon_ce_save_dir', '@beacon_ce_view_dir', null,
                                null, '@beacon_ce_url',
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
        $this->addFileAttribute('horizontal_picture', '@beacon_ce_save_dir', '@beacon_ce_view_dir',
                                null, null, '@beacon_ce_url',
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

    public function behaviors() {
        return [
            FileSaveBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon_content_elements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['beacon_id'], 'integer'],
            [['description', 'additional_info'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['picture','horizontal_picture'],'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            [['link'], 'string', 'max' => 512],
            [['beacon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beacons::className(), 'targetAttribute' => ['beacon_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'beacon_id' => Yii::t('app', 'Beacon ID'),
            'title' => Yii::t('app', 'Title'),
            'link' => Yii::t('app', 'Link'),
            'description' => Yii::t('app', 'Description'),
            'picture' => Yii::t('app', 'Picture'),
            'horizontal_picture' => Yii::t('app', 'Horizontal Picture'),
            'additional_info' => Yii::t('app', 'Additional Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacon()
    {
        return $this->hasOne(Beacons::className(), ['id' => 'beacon_id']);
    }

    public function fields() {
        $fields = parent::fields();
        $fields['absolutePicture'] = 'absolutePicture';
        $fields['absoluteHorizontalPicture'] = 'absoluteHorizontalPicture';
        return $fields;
    }

    public function getAbsoluteHorizontalPicture() {
        if(Yii::$app->request instanceof Request)
            return Yii::$app->request->getHostInfo() . $this->getFile('horizontal_picture');
        else return null;
    }

    public function getAbsolutePicture() {
        if(Yii::$app->request instanceof Request)
            return Yii::$app->request->getHostInfo() . $this->getFile('picture');
        else return null;
    }
}
