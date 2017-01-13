<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 12.04.2015
 * Time: 17:42
 */
namespace app\models;

use dezmont765\yii2bundle\components\Alert;
use DateTime;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\StaleObjectException;
use yii\db\Transaction;

/**
 *
 * @class MainActiveRecord
 * @property Transaction $transaction
 * @property array $changedAttributes
 */
class MainActiveRecord extends ActiveRecord
{
    public $is_saved = null;
    private $transaction = null;
    private $_oldAttributes;
    protected $_changed_attributes = [];


    public function getChangedAttributes() {
        return $this->_changed_attributes;
    }


    public function setChangedAttributes($attributes) {
        $this->_changed_attributes = $attributes;
    }


    protected function updateInternal($attributes = null) {
        if(!$this->beforeSave(false)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if(empty($values)) {
            $this->afterSave(false, $values);
            return 0;
        }
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if($lock !== null) {
            $values[$lock] = $this->$lock + 1;
            $condition[$lock] = $this->$lock;
        }
        $rows = $this->updateAll($values, $condition);
        if($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }
        if(isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }
        $changedAttributes = [];
        foreach($values as $name => $value) {
            $changedAttributes[$name] =
                isset($this->_oldAttributes[$name]) && array_key_exists($name, $this->_oldAttributes)
                    ? $this->_oldAttributes[$name] : null;
            $this->_oldAttributes[$name] = $value;
        }
        $this->changedAttributes = $changedAttributes;
        $this->afterSave(false, $this->changedAttributes);
        return $rows;
    }


    public function searchByAttribute($attribute, $value, array $additional_criteria = []) {
        $query = self::find();
        $query->filterWhere(['like', $attribute, $value]);
        if(count($additional_criteria)) {
            foreach($additional_criteria as $criteria) {
                $query->andFilterWhere($criteria);
            }
        }
        return $query->all();
    }


    public function searchByIds(array $ids) {
        $query = self::find();
        $query->filterWhere(['id' => $ids]);
        return $query->all();
    }


    protected function initLocalTransaction() {
        if(!Yii::$app->db->getTransaction()) {
            $this->transaction = Yii::$app->db->beginTransaction();
        }
    }


    protected function commitLocalTransaction() {
        if(self::isLocalTransactionAccessible()) {
            $this->transaction->commit();
        }
    }


    protected function rollbackLocalTransaction() {
        if(self::isLocalTransactionAccessible()) {
            $this->transaction->rollBack();
        }
    }


    protected function isLocalTransactionAccessible() {
        $is_accessible = !is_null($this->transaction);
        return $is_accessible;
    }


    /** this might look unnecessary but it disables useless typecasting from ActiveRecord class
     * @param BaseActiveRecord $record
     * @param array $row
     */
    public static function populateRecord($record, $row) {
        BaseActiveRecord::populateRecord($record, $row);
    }


    public function save($runValidation = true, $attributeNames = null) {
        $this->initLocalTransaction();
        try {
            $is_saved = parent::save($runValidation, $attributeNames);
            if($this->is_saved === null) {
                $this->is_saved = $is_saved;
            }
        }
        catch(Exception $e) {
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


    public static function convertDate($model, $attribute, $current_format, $desired_format) {
        if(!empty($model->$attribute)) {
            $buffer = $model->$attribute;
            $model->$attribute = DateTime::createFromFormat($current_format, $model->$attribute);
            if($model->$attribute instanceof DateTime) {
                $model->$attribute = $model->$attribute->format($desired_format);
            }
            else $model->$attribute = $buffer;
        }
    }


    public static function _formName() {
        /**
         * @var ActiveRecord $model
         */
        $class = get_called_class();
        $model = new $class;
        return $model->formName();
    }


    public static function getPrefixedAttribute($attribute) {
        return static::_formName() . '_' . $attribute;
    }


    public function unsetAttributes($names = null) {
        if($names === null) {
            $names = $this->attributes();
        }
        foreach($names as $name) {
            $this->$name = null;
        }
    }


    public function safeAttributesExcept($except = []) {
        $except = array_flip($except);
        $scenario = $this->getScenario();
        $scenarios = $this->scenarios();
        if(!isset($scenarios[$scenario])) {
            return [];
        }
        $attributes = [];
        foreach($scenarios[$scenario] as $attribute) {
            if(!isset($except[$attribute])) {
                if($attribute[0] !== '!') {
                    $attributes[] = $attribute;
                }
            }
        }
        return $attributes;
    }


}