<?php
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:25
 * Class SaveFileBehavior
 *
 * you need to define 2 aliases file_save_dir and file_view_dir
 */

class SaveFileBehavior extends \yii\base\Behavior
{
    public $file_attribute;

    private  $file_instance;

    public function behaviors()
    {
        
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE=> 'afterSave',
        ];
    }

    public function beforeValidate($event)
    {
        $attribute = $this->file_attribute;
        if($this->file_instance instanceof UploadedFile)
        {
            if(!$this->owner->isNewRecord && is_file($this->owner->getFileSavePath() . $this->owner->oldAttributes[$attribute]))
            {
                unlink($this->owner->getFileSavePath() . $this->owner->oldAttributes[$attribute]);
            }
            $this->owner->$attribute = $this->owner->getFileName() .$this->file_instance->extension;
        }
    }

    public function afterSave($event)
    {
        $attribute = $this->file_attribute;
        if(!is_dir($this->owner->getFileSavePath()))
        {
            FileHelper::createDirectory($this->owner->getFileSavePath());
        }
        if(!is_dir($this->owner->getFileViewDir()))
        {
            symlink($this->owner->getFileSaveDir(),$this->owner->getFileViewDir());
        }
        if($this->file_instance instanceof UploadedFile)
        {
            $this->file_instance->saveAs($this->owner->getFileSavePath() . $this->owner->$attribute);
        }
    }

    public function getFileSaveDir()
    {
        return Yii::getAlias('@file_save_dir');
    }

    public function getFileViewDir()
    {
        return Yii::getAlias('@file_view_dir');
    }

    public function getFileViewUrl()
    {
        return Yii::getAlias('@file_view_url');
    }

    public function getFileSavePath()
    {
        return self::getFileSaveDir() . $this->owner->id . DIRECTORY_SEPARATOR;
    }

    public function getFileViewPath()
    {
        return self::getFileViewUrl() . $this->owner->id . '/';
    }

    public function getFile()
    {
        $attribute = $this->file_attribute;
        return self::getFileViewPath() . $this->owner->$attribute;
    }

    public function getFileName()
    {
        return Yii::$app->security->generateRandomString(16) . '.';
    }


}