<?php

namespace app\models;

use app\components\Alert;
use app\commands\RbacController;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\rbac\Role;
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
 * @property string $role
 * @property string $logged
 *
 * @property BeaconBindings[] $beaconBindings
 */
class Users extends ActiveRecord implements IdentityInterface
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    ];

    public static function  getStatus($status)
    {
        return (isset(self::$statuses[$status]) ? self::$statuses[$status] : null);
    }

    public function getCurrentStatus()
    {
        return (isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null);
    }

    public static function  getRole($role)
    {
        return (isset(self::$roles[$role]) ? self::$roles[$role] : null);
    }

    public function getCurrentRole()
    {
        return (isset(self::$roles[$this->role]) ? self::$roles[$this->role] : null);
    }

    public function getAvailableGroups()
    {
        $role_groups =  [
            RbacController::user => [],
            RbacController::admin => [RbacController::user => 'User'],
            RbacController::super_admin =>[RbacController::admin => 'Admin', RbacController::user => 'User'],
        ];
        return $role_groups[$this->role];
    }

    public static $roles = [
        RbacController::user => 'User',
        RbacController::admin => 'Admin',
        RbacController::super_admin => 'Super Admin',
    ];

    public static $status_colors = [
        self::STATUS_INACTIVE  => 'red',
        self::STATUS_ACTIVE => 'green'
    ];
    private static $_logged_user = null;
    private static $_is_need_update = false;
    public $rememberMe;
    public $passwordConfirm;
    public $group_token = null;
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
            ['email','email'],
            [['email','password'],'required'],
            ['email','unique','on'=>['insert','register']],
            ['role','default','value'=> RbacController::user],
            [['passwordConfirm','group_token'],'required','on'=>'register'],
            ['group_token','exist','targetClass'=>Groups::className(),'targetAttribute'=>'token','on'=>'register'],
            ['passwordConfirm','compare','compareAttribute'=>'password','on'=>'register'],
            ['rememberMe', 'boolean'],
            [['group_token'], 'string', 'max' => 64],
            [['name', 'email'], 'string', 'max' => 50],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 256],

        ];
    }

    public function afterFind()
    {
        self::resolveRoles();
    }


    public function afterValidate()
    {
        if ($this->isNewRecord) {
            $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        }
        return false;
    }

    protected function resolveRoles()
    {
        /**@var \yii\rbac\DbManager $auth*/
//        $auth = Yii::$app->authManager;
//        if(!$auth->getAssignment($this->role,$this->id))
//        {
//            $role = $auth->getRole($this->role);
//            if($role instanceof Role)
//            {
//                $auth->revokeAll($this->id);
//                $auth->assign($role, $this->id);
//                $auth->invalidateCache();
//            }
//        }
    }

    public function afterSave($insert,$oldAttributes)
    {
        self::resolveRoles();
        $group = Groups::findOne(['token'=>$this->group_token]);
        if($group instanceof Groups)
        {

            $user_binding = UserBindings::findOne(['user_id'=>$this->id,'group_id'=>$group->id]);
            if(!($user_binding instanceof UserBindings))
                $user_binding = new UserBindings();
            $user_binding->user_id = $this->id;
            $user_binding->group_id = $group->id;
            if($user_binding->save())
            {
                Alert::addSuccess('User has been successfully joined to new group');
            }
        }
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
    public function getUserBindings()
    {
        return $this->hasMany(UserBindings::className(), ['user_id' => 'id']);
    }

    /**@return ActiveQuery*/
    public function getGroups()
    {
        return $this->hasMany(Groups::className(),['id'=>'group_id'])
            ->via('userBindings');
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
        return static::findOne(['id'=>$id,'status'=>self::STATUS_ACTIVE]);
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
        return self::findOne(['email'=>$email,'status'=>self::STATUS_ACTIVE]);
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
        {
            $user->save();
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        else
        {
            $this->addError('email','Your login/password is incorrect');
            $this->addError('password','Your login/password is incorrect');
            return false;
        }
    }


    /**
     * @param bool $safe
     * @return null|Users
     * @throws NotFoundHttpException
     */
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

    public function getEditableRoles()
    {
        $editable_roles = [
          RbacController::super_admin => [RbacController::admin,RbacController::user],
          RbacController::admin => [RbacController::user],
          RbacController::user => []
        ];
        return isset($editable_roles[$this->role]) ? $editable_roles[$this->role] : [];
    }

    public function  canEdit($checking_role)
    {
        foreach (self::getEditableRoles() as  $role)
        {
            if($checking_role == $role)
                return true;
        }
        return false;
    }

    public function canDelete($checking_role)
    {
        return self::canEdit($checking_role);
    }

    public function canEditBeacon(Beacons $beacon)
    {
        $beacon_query = self::getBeaconsQuery();
        $beacon_query->andFilterWhere(['id'=>$beacon->id]);
        $result = $beacon_query->one();
        return ($result instanceof Beacons);
    }

    public function getBeaconsQuery()
    {
        $query = Beacons::find();
        $user = $this;
        $query->joinWith([
                             'groups' => function(ActiveQuery $query) use ($user)
                             {
                                 $query->joinWith([
                                                      'users'=>function(ActiveQuery $query) use ($user)
                                                      {
                                                          $query->andFilterWhere(['users.id'=>$user->id]);
                                                      }
                                                  ]);
                             }
                         ]);
        return $query;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
