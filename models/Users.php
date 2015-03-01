<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 *
 * @property BeaconBindings[] $beaconBindings
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{

    private static $_logged_user = null;
    private static $_is_need_update = false;
    public $rememberMe;
    public $passwordConfirm;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email','password'],'required'],
            ['email','unique','on'=>['insert','register']],
            ['passwordConfirm','required','on'=>'register'],
            ['passwordConfirm','compare','compareAttribute'=>'password','on'=>'register'],
            ['rememberMe', 'boolean'],
            [['name', 'email'], 'string', 'max' => 50],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 256],

        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'passwordConfirm' => Yii::t('app', 'Password confirm'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'access_token' => Yii::t('app', 'Access Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeaconBindings()
    {
        return $this->hasMany(BeaconBindings::className(), ['user_id' => 'id']);
    }


    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }


    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
       return $this->auth_key;
    }


    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }



    public static  function findByEmail($email)
    {
        return self::findOne(['email'=>$email]);
    }



    public function login()
    {
        $user = $this->findByEmail($this->email);
        if(!$user)
        {
            $this->addError('email','Your login/password is incorrect');
            $this->addError('password','Your login/password is incorrect');
            return false;
        }
        if($this->scenario == 'register')
           $this->password = $_POST[$this->formName()]['password'];
        if(\Yii::$app->getSecurity()->validatePassword($this->password,$user->password))
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        else
        {
            $this->addError('email','Your login/password is incorrect');
            $this->addError('password','Your login/password is incorrect');
            return false;
        }
    }


    public static  function getLogged($safe = false)
    {
        if(!self::$_logged_user || self::$_is_need_update)
        {
            self::$_logged_user = self::findOne(['id'=>Yii::$app->user->id]);
            if($safe && !(self::$_logged_user  instanceof Users))
            {
                throw new NotFoundHttpException;
            }
        }
        return self::$_logged_user ;
    }
}
