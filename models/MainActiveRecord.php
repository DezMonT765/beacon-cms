<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 12.04.2015
 * Time: 17:42
 */
namespace app\models;
use app\components\Alert;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Transaction;

/**
 *
 * @class MainActiveRecord
 * @property Transaction $transaction
 */
class MainActiveRecord extends ActiveRecord
{
    public $is_saved = null;
    private $transaction = null;
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
            $this->transaction->rollBack();
        }
    }

    protected function isLocalTransactionAccessible()
    {
        $is_accessible = !is_null($this->transaction);
        return  $is_accessible;
    }

    /** this looks unnecessary but it disables useless typecasting from ActiveRecord class
     * @param BaseActiveRecord $record
     * @param array $row
     */
    public static function populateRecord($record,$row) {
        BaseActiveRecord::populateRecord($record, $row);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->initLocalTransaction();
        try {
            $is_saved = parent::save($runValidation, $attributeNames);
            if($this->is_saved === null)
                $this->is_saved = $is_saved;
        }
        catch (Exception $e) {
            Alert::addError($e->getMessage(),
                            ['class' => self::className(),
                             'line' => $e->getLine(),
                             'file' => $e->getFile(),
                             'trace' => $e->getTraceAsString(),
                             'id' => $this->id,
                             'isNewRecord' => $this->isNewRecord,
                             'errors' => $this->errors]);
            $this->rollbackLocalTransaction();
            $this->is_saved = false;
        }
        if($this->hasErrors() || count(Alert::getErrors())) {
            $this->rollbackLocalTransaction();
            $this->is_saved = false;
        }
        else {
            $this->commitLocalTransaction();
            $this->is_saved = true;
        }

        return $this->is_saved;
    }


}