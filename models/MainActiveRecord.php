<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 12.04.2015
 * Time: 17:42
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;

class MainActiveRecord extends ActiveRecord
{
    public $transaction;

    public function searchByAttribute($attribute,$value)
    {
        $query = self::find();
        $query->filterWhere(['like',$attribute, $value]);
        return $query->all();
    }

    public function searchByIds(array $ids)
    {
        $query = self::find();
        $query->filterWhere(['id'=>$ids]);
        return $query->all();
    }


    protected function initLocalTransaction()
    {
            $this->transaction = Yii::$app->db->beginTransaction();
    }

    protected  function commitLocalTransaction()
    {
        if(self::isLocalTransactionAccessible())
        {
            $this->transaction->commit();
        }
    }

    protected function rollbackLocalTransaction()
    {
        if(self::isLocalTransactionAccessible())
        {
            $this->transaction->rollback();
        }
    }

    protected function isLocalTransactionAccessible()
    {
        $is_accessible = !is_null($this->transaction);
        return  $is_accessible;
    }


}