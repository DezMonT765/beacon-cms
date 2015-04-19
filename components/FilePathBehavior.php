<?php
namespace app\components;
use Yii;
use yii\base\Behavior;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:25
 *
 * you need to define 2 aliases file_save_dir and file_view_dir
 */

class FilePathBehavior extends Behavior
{
    public $file_attribute;

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