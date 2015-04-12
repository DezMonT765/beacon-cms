<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $passwordConfirm;

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
        ];
    }

    /**
     * Signs user up.
     *
     * @return Users|null the saved model or null if saving fails
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new Users(['scenario'=>'register']);
            $user->email = $this->email;
            $user->password = $this->password;
            $user->passwordConfirm = $this->passwordConfirm;
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
