<?php
namespace app\models;

use app\components\UUID;
use yii\base\Exception;
use Yii;
use yii\helpers\Url;

/**
 * Signup form
 */
class PromoForm extends RegisterForm
{
    public $username;
    public $email;
    public $password;
    public $passwordConfirm;
    public $beacon_count = 1;
    public $role = null;
    public $terms_agree = 0;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\Users', 'message' => 'This email address has already been taken.'],
            [['password','passwordConfirm'],'required'],
            ['password', 'string'],
            ['passwordConfirm','compare','compareAttribute'=>'password',],
            ['beacon_count','safe'],
            ['terms_agree','compare','compareValue'=>(int)true,'message' => 'You must agree with terms to proceed']
        ];
    }



    /**
     * Signs user up.
     *
     * @return Users|null the saved model or null if saving fails
     */
    public function register()
    {
        $user = null;
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $group = new Groups();
            $group->name = $this->email;

            if($group->save())
            {
                $user = parent::register($group->id);
                for ($i = 0; $i < $this->beacon_count; $i++)
                {
                    $beacon = new Beacons();
                    $beacon->name = Yii::$app->security->generateRandomString(16);
                    $beacon->title = "Such title $i";
                    $beacon->description = "So description $i";
                    $beacon->minor = $group->id . $user->id . $i;
                    $beacon->major = $group->id. $user->id . $i;
                    $beacon->place = "Wow Place $i";
                    $beacon->uuid = UUID::v4();
                    $beacon->groupToBind = $group->id;
                    $beacon->save();
                }

            }
            $transaction->commit();
            return $user;
        }
        catch(Exception $e) {
            if($transaction->isActive) {
                $transaction->rollBack();
            }
            return null;
        }
    }
}
