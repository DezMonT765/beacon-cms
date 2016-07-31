<?php
namespace app\components;

use app\helpers\Helper;
use app\models\MainActiveRecord;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:25
 * Class FileSaveBehavior
 * @property MainActiveRecord $owner
 * @property MainActiveRecord $lama
 */
class FileSaveBehavior extends Behavior
{
    public $crop;

    const INSTANCE = 'instance';
    const FILE_SAVE_DIR = 'file_save_dir';
    const FILE_VIEW_DIR = 'file_view_dir';
    const STORE_MODEL_CLASS = 'store_model_class';
    const STORE_RELATION_ATTRIBUTE = 'store_relation_attribute';
    const STORE_TYPE_ATTRIBUTE = 'store_type_attribute';
    const STORE_MODEL_ATTRIBUTE = 'store_model_attribute';
    const STORE_MODEL_TYPE = 'store_model_type';
    const FILE_VIEW_URL = 'file_view_url';
    const BACKEND_VIEW_DIR = 'backend_view_dir';
    const FRONTEND_VIEW_DIR = 'frontend_view_dir';
    const ON_SAVE = 'on_save';
    const IS_MULTIPLE = 'is_multiple';
    const IS_ENCRYPT = 'is_encrypt';
    const ON_DELETE = 'on_delete';
    public $file_attributes;


    /** @method addFileAttribute
     * @param $attribute
     * @param $file_save_dir
     * @param $file_view_dir
     * @param $backend_view_dir
     * @param $frontend_view_dir
     * @param $file_view_url
     * @param callable $on_save
     * @param bool $is_encrypt
     * @param callable $on_delete
     */
    public function addFileAttribute($attribute, $file_save_dir, $file_view_dir, $backend_view_dir, $frontend_view_dir, $file_view_url, callable $on_save = null, $is_encrypt = false,callable $on_delete = null) {
        $this->file_attributes[$attribute][self::FILE_SAVE_DIR] = $file_save_dir;
        $this->file_attributes[$attribute][self::FILE_VIEW_DIR] = $file_view_dir;
        $this->file_attributes[$attribute][self::FILE_VIEW_URL] = $file_view_url;
        $this->file_attributes[$attribute][self::BACKEND_VIEW_DIR] = $backend_view_dir;
        $this->file_attributes[$attribute][self::FRONTEND_VIEW_DIR] = $frontend_view_dir;
        $this->file_attributes[$attribute][self::ON_SAVE] = $on_save;
        $this->file_attributes[$attribute][self::ON_DELETE] = $on_delete;
        $this->file_attributes[$attribute][self::IS_ENCRYPT] = $is_encrypt;
    }


    public function addFilesAttribute($attribute, $store_model_class, $store_model_attribute, $store_model_type, $store_relation_attribute, $store_type_attribute,
                                      $file_save_dir, $file_view_dir, $backend_view_dir,
                                      $frontend_view_dir, $file_view_url, callable $on_save = null, $is_encrypt = false) {
        $this->file_attributes[$attribute][self::STORE_MODEL_CLASS] = $store_model_class;
        $this->file_attributes[$attribute][self::STORE_MODEL_ATTRIBUTE] = $store_model_attribute;
        $this->file_attributes[$attribute][self::STORE_RELATION_ATTRIBUTE] = $store_relation_attribute;
        $this->file_attributes[$attribute][self::STORE_MODEL_TYPE] = $store_model_type;
        $this->file_attributes[$attribute][self::STORE_TYPE_ATTRIBUTE] = $store_type_attribute;
        $this->file_attributes[$attribute][self::FILE_SAVE_DIR] = $file_save_dir;
        $this->file_attributes[$attribute][self::FILE_VIEW_DIR] = $file_view_dir;
        $this->file_attributes[$attribute][self::FILE_VIEW_URL] = $file_view_url;
        $this->file_attributes[$attribute][self::BACKEND_VIEW_DIR] = $backend_view_dir;
        $this->file_attributes[$attribute][self::FRONTEND_VIEW_DIR] = $frontend_view_dir;
        $this->file_attributes[$attribute][self::ON_SAVE] = $on_save;
        $this->file_attributes[$attribute][self::IS_MULTIPLE] = true;
        $this->file_attributes[$attribute][self::IS_ENCRYPT] = $is_encrypt;
    }


    public function isMultiple($file_attribute) {
        $is_multiple = null;
        if(isset($this->file_attributes[$file_attribute][self::IS_MULTIPLE])) {
            $is_multiple = $this->file_attributes[$file_attribute][self::IS_MULTIPLE];
        }
        return $is_multiple;
    }


    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ];
    }

    public function afterFind() {
        foreach($this->file_attributes as $file_attribute => $property) {
            if($this->isMultiple($file_attribute)) {
                $files = $this->findRelatedModel($file_attribute);
                $store_model_attribute = $this->file_attributes[$file_attribute][self::STORE_MODEL_ATTRIBUTE];
                if(count($files)) {
                    $this->owner->$file_attribute = [];
                    foreach($files as $file) {
                        $this->owner->{$file_attribute}[$file->id] = $file->$store_model_attribute;
                    }
                }
            }
        }
    }


    public function afterDeleteProcess($attribute) {
        FileHelper::removeDirectory($this->getFileSavePath($attribute));
    }


    protected function deleteOldFile($file_path) {
        if(is_file($file_path)) {
            unlink($file_path);
        }
    }


    public function afterDelete() {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::afterDeleteProcess($file_attribute);
        }
    }

    public function deleteSimilarFiles($file) {
        $name = pathinfo($file,PATHINFO_FILENAME);
        $path = pathinfo($file,PATHINFO_DIRNAME);
        $files = glob($path.DIRECTORY_SEPARATOR . $name . '*');
        foreach($files as $file) {
            $this->removeFile($file);
            if(is_dir($file)) {
                FileHelper::removeDirectory($file);
            }
        }
    }

    public function beforeValidationProcess($attribute) {
        if($this->isMultiple($attribute)) {
            $this->owner->$attribute = UploadedFile::getInstances($this->owner, $attribute);
        }
        else {
            $this->owner->$attribute = UploadedFile::getInstance($this->owner, $attribute);
        }
    }


    public function beforeValidate($event) {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::beforeValidationProcess($file_attribute);
        }
    }


    public function removeFile($file_path) {
        if(is_file($file_path)) {
            unlink($file_path);
            return true;
        }
        return false;
    }


    public function afterValidationProcessMultiple($attribute) {
        $this->file_attributes[$attribute][self::INSTANCE] = UploadedFile::getInstances($this->owner, $attribute);
        $instances = $this->file_attributes[$attribute][self::INSTANCE];
        if(count($instances) > 0) {
            if(!$this->owner->isNewRecord) {
                /** @var string|MainActiveRecord $store_model_class */
                $store_model_attribute = $this->file_attributes[$attribute][self::STORE_MODEL_ATTRIBUTE];
                $files = $this->findRelatedModel($attribute);
                foreach($files as $file) {
                    if($file->delete()) {
                        if(!empty($store_model_attribute)) {
                            $this->removeFile($this->getFileSavePath($attribute). $file->$store_model_attribute);
                            $this->deleteSimilarFiles($this->getFileSavePath($attribute). $file->$store_model_attribute);
                        }
                    }
                }
            }
        }
    }


    public function findRelatedModel($attribute) {
        if($this->isMultiple($attribute)) {
            $store_model_class = $this->file_attributes[$attribute][self::STORE_MODEL_CLASS];
            $store_relation_attribute = $this->file_attributes[$attribute][self::STORE_RELATION_ATTRIBUTE];
            $store_type_attribute = $this->file_attributes[$attribute][self::STORE_TYPE_ATTRIBUTE];
            $store_model_type = $this->file_attributes[$attribute][self::STORE_MODEL_TYPE];
            $files = $store_model_class::find()
                                       ->where([$store_relation_attribute => $this->owner->id])
                                       ->andWhere([$store_type_attribute => $store_model_type])
                                       ->all();
            return $files;
        }
        return [];
    }


    public function afterValidationProcessSingle($attribute) {
        $this->file_attributes[$attribute][self::INSTANCE] = UploadedFile::getInstance($this->owner, $attribute);
        if($this->file_attributes[$attribute][self::INSTANCE] instanceof UploadedFile) {
            if(!$this->owner->isNewRecord) {
                if(isset($this->owner->oldAttributes[$attribute])) {
                    $this->removeFile($this->getFileSavePath($attribute) . $this->owner->oldAttributes[$attribute]);
                }
            }
            $this->owner->$attribute =
                $this->getFileName() . '.' . $this->file_attributes[$attribute][self::INSTANCE]->extension;
        }
        else {
            if(isset($this->owner->oldAttributes[$attribute]) && $this->owner->oldAttributes) {
                $this->owner->$attribute = $this->owner->oldAttributes[$attribute];
            }
        }
    }


    public function getObjectDir($attribute) {
        if(isset($this->file_attributes[$attribute][self::IS_ENCRYPT])
           && $this->file_attributes[$attribute][self::IS_ENCRYPT]
        ) {
            return Encryption::encode($this->owner->id);
        }
        else return $this->owner->id;
    }


    public function afterValidationProcess($attribute) {
        if($this->isMultiple($attribute)) {
            $this->afterValidationProcessMultiple($attribute);
        }
        else {
            $this->afterValidationProcessSingle($attribute);
        }
    }


    public function afterValidate($event) {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::afterValidationProcess($file_attribute);
        }
    }


    /**
     * Creates a tunnel between web accessible folder and real folder, using symlinks.
     * 1) Checks and creates folder if needed;
     * 2) Checks and creates frontend/backend symlinks if needed, for web access purposes.
     * @param $file_save_path
     * @param $file_save_dir
     * @param $backend_view_dir
     * @param $frontend_view_dir
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public static function prepareFolderTunnel($file_save_path, $file_save_dir, $backend_view_dir = null, $frontend_view_dir = null) {
        if(!is_dir($file_save_path)) {
            FileHelper::createDirectory($file_save_path);
        }
        if($backend_view_dir !== null) {
            if(!Helper::_is_link($backend_view_dir)) {
                if(is_dir($backend_view_dir)) {
                    FileHelper::removeDirectory($backend_view_dir);
                }
                symlink($file_save_dir, $backend_view_dir);
            }
        }
        if($frontend_view_dir !== null) {
            if(!Helper::_is_link($frontend_view_dir)) {
                if(is_dir($frontend_view_dir)) {
                    FileHelper::removeDirectory($frontend_view_dir);
                }
                symlink($file_save_path, $frontend_view_dir);
            }
        }
    }


    public function postSavingProcessMultiple($attribute) {
        if(isset($this->file_attributes[$attribute][self::INSTANCE])) {
            $instances = $this->file_attributes[$attribute][self::INSTANCE];
            if(is_array($instances) && count($instances)) {
                self::prepareFolderTunnel($this->getFileSavePath($attribute),
                                          $this->getFileSaveDir($attribute),
                                          $this->getBackendViewDir($attribute),
                                          $this->getFrontendViewDir($attribute)
                );
                $store_model_class = $this->file_attributes[$attribute][self::STORE_MODEL_CLASS];
                $store_type_attribute = $this->file_attributes[$attribute][self::STORE_TYPE_ATTRIBUTE];
                $store_model_type = $this->file_attributes[$attribute][self::STORE_MODEL_TYPE];
                $store_model_attribute = $this->file_attributes[$attribute][self::STORE_MODEL_ATTRIBUTE];
                $store_relation_attribute = $this->file_attributes[$attribute][self::STORE_RELATION_ATTRIBUTE];
                foreach($instances as $instance) {
                    if($instance instanceof UploadedFile) {
                        $store_model = new $store_model_class;
                        $file_name = $this->getFileName();
                        $file_extension = $instance->extension;
                        $store_model->$store_model_attribute = $file_name . '.' . $file_extension;
                        $store_model->$store_type_attribute = $store_model_type;
                        $store_model->$store_relation_attribute = $this->owner->id;
                        if($store_model->save()) {
                            $file_full_path = $this->getFileSavePath($attribute) . $store_model->$store_model_attribute;
                            if($instance->saveAs($file_full_path)) {
                                if(isset(self::getFileAttributeParams($attribute)[self::ON_SAVE])
                                   && is_callable(self::getFileAttributeParams($attribute)[self::ON_SAVE])
                                ) {
                                    call_user_func_array(
                                        self::getFileAttributeParams($attribute)[self::ON_SAVE],
                                        [$attribute,$file_full_path, $this->getFileSavePath($attribute),$file_name,$file_extension]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public function postSavingProcessSingle($attribute) {
        if(isset(self::getFileAttributeParams($attribute)[self::INSTANCE])) {
            if($this->file_attributes[$attribute][self::INSTANCE] instanceof UploadedFile) {
                self::prepareFolderTunnel($this->getFileSavePath($attribute),
                                          $this->getFileSaveDir($attribute),
                                          $this->getBackendViewDir($attribute),
                                          $this->getFrontendViewDir($attribute)
                );
                if($this->file_attributes[$attribute][self::INSTANCE]->saveAs($this->getFileSavePath($attribute)
                                                                              . $this->owner->$attribute)
                ) {
                    if(isset(self::getFileAttributeParams($attribute)[self::ON_SAVE])
                       && is_callable(self::getFileAttributeParams($attribute)[self::ON_SAVE])
                    ) {
                        call_user_func_array(
                            self::getFileAttributeParams($attribute)[self::ON_SAVE],
                            [$attribute, $this->getFileSavePath($attribute) . $this->owner->$attribute,$this->owner->$attribute]);
                    }
                }
            }
        }
    }


    public function postSavingProcess($attribute) {

        if($this->isMultiple($attribute)) {
            $this->postSavingProcessMultiple($attribute);
        }
        else {
            $this->postSavingProcessSingle($attribute);
        }
    }


    public function afterSave($event) {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::postSavingProcess($file_attribute);
        }
    }


    public function getFileInstance($file_attribute) {
        if(isset($this->file_attributes[$file_attribute])) {
            $this->file_attributes[$file_attribute][self::INSTANCE];
        }
    }


    public function getFileAttributeParams($file_attribute) {
        if(isset($this->file_attributes[$file_attribute])) {
            return $this->file_attributes[$file_attribute];
        }
        else return [];
    }


    public function getBackendViewDir($file_attribute) {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::BACKEND_VIEW_DIR])) {
            $path = Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::BACKEND_VIEW_DIR]);
        }
        return $path;
    }


    public function getFrontendViewDir($file_attribute) {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::FRONTEND_VIEW_DIR])) {
            $path = Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FRONTEND_VIEW_DIR]);
        }
        return $path;
    }


    /**@method getFileSaveDir
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileSaveDir($file_attribute) {
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_SAVE_DIR])) {
            return Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_SAVE_DIR]);
        }
        else throw new InvalidParamException();
    }


    /**@method getFileViewDir
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileViewDir($file_attribute) {
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_DIR])) {
            return Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_DIR]);
        }
        else throw new InvalidParamException();
    }


    /**@method getFileViewUrl
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileViewUrl($file_attribute) {
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_URL])) {
            return Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_URL]);
        }
        else throw new InvalidParamException();
    }


    /** @method getFileSavePath
     * @param $file_attribute
     * @return string
     */
    public function getFileSavePath($file_attribute) {
        return self::getFileSaveDir($file_attribute) . self::getObjectDir($file_attribute) . DIRECTORY_SEPARATOR;
    }


    /** @method getFileViewPath
     * @param $file_attribute
     * @return string
     */
    public function getFileViewPath($file_attribute) {
        return self::getFileViewUrl($file_attribute) . '/' . self::getObjectDir($file_attribute) . '/';
    }


    /** @method getFileViewPath
     * @param $file_attribute
     * @param bool $scheme
     * @return string
     */
    public function getFile($file_attribute, $scheme = false) {
        $result = null;
        if($this->isMultiple($file_attribute)) {
            $result = [];
            if(is_array($this->owner->$file_attribute) && count($this->owner->$file_attribute)) {
                $files = $this->owner->$file_attribute;
                foreach($files as $file) {
                    $result[] = $this->getFileByName($files,$file_attribute,$scheme);
                }
            }
            else {
                $files = $this->findRelatedModel($file_attribute);
                $store_model_attribute = $this->file_attributes[$file_attribute][self::STORE_MODEL_ATTRIBUTE];
                foreach($files as $file) {
                    $result[] = $this->getFileByName($file->$store_model_attribute,$file_attribute,$scheme);
                }
            }

            
        }
        else {
            $result = $this->getFileByName($this->owner->$file_attribute,$file_attribute,$scheme);
        }
        return $result;
    }
    
    public function getFileByName($name,$attribute,$scheme = false) {
        $file = self::getFileViewPath($attribute) . $name;
        if($scheme) {
            return Url::to([$file], $scheme);
        }
        else return $file;
    }


    /** @method getFileName
     * @return string
     */
    public function getFileName() {
        return Yii::$app->security->generateRandomString(16);
    }


    public function saveFiles() {
        $this->owner->id = Yii::$app->security->generateRandomString(8);
        foreach($this->file_attributes as $file_attribute => $property) {
            self::afterValidationProcess($file_attribute);
            self::postSavingProcess($file_attribute);
        }
    }


}