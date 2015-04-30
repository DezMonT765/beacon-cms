<?php
namespace app\models;
use app\components\FilePathBehavior;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 30.04.2015
 * Time: 10:03
 */

class BeaconMapLoad extends Model
{
    public $file_instance;
    public $file ;

    public function rules()
    {
        ['map','required'];
    }

    public function behaviors()
    {
        return [
            'filePath' => [
                'class' => FilePathBehavior::className(),
                'file_attribute' => 'file'
            ]
        ];
    }

    public function saveMap()
    {

        if($this->file_instance instanceof UploadedFile)
        {
            if(is_file($this->getFileSavePath()))
            {
                unlink($this->getFileSavePath(). $this->getFileName());
            }

            $this->file= $this->getFileName() .$this->file_instance->extension;

            return $this->file_instance->saveAs($this->getFileSavePath().$this->file);
        }
        else return false;
    }

    public function getFileSavePath()
    {
        return self::getFileSaveDir() . DIRECTORY_SEPARATOR;
    }

    public function getFileViewPath()
    {
        return self::getFileViewUrl() .  '/';
    }

    public function getFile()
    {
        return self::getFileViewPath() . self::getFileName();
    }

    public function getFileName()
    {
        return 'beacon_map';
    }


}