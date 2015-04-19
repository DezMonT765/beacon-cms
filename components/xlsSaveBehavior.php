<?php
use yii\base\Behavior;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 18.03.2015
 * Time: 14:18
 * @property xlsImport $owner
 */

class xlsSaveBehavior extends Behavior
{
    public function saveByMode($model,$old_model)
    {
        try
        {
            if($old_model)
            {
                if($this->owner->isUpdate())
                {
                    $old_model->attributes = $model->attributes;
                    $this->owner->saveModel($old_model);
                }
                else
                {
                    $this->owner->warnings[] = 'Object with id = '.$old_model->id . ' already exist';
                }
            }
            else
            {
                $this->owner->saveModel($model);
            }
        }
        catch(Exception $e)
        {
            $this->owner->errors[] = $e->getMessage();
        }
    }

    public static  function getClass()
    {
        return get_called_class();
    }
}