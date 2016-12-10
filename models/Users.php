<?php
namespace app\models;
//use app\components\Alert;
//use app\commands\RbacController;
use app\commands\RbacController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
 * @property string $groupsToBind
 * @property string $language
 * @property string $password_reset_token
 *
 *
 * relations
 * @property BeaconBindings[] $beaconBindings
 * @property BeaconBindings[] $groups
 */
class Users extends ActiveRecord implements IdentityInterface
{

    const super_admin = 'super_admin';
    const admin = 'admin';
    const user = 'user';
    const promo_user = 'promo_user';
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const PASSWORD_CHANGE_SCENARIO = 'password-change';

    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    ];




    public $_groupsToBind = null;

    public function setGroupsToBind($groups) {
        $this->_groupsToBind = $groups;
    }

    public function getGroupsToBind() {
        if($this->_groupsToBind !== null) {
            $result = $this->_groupsToBind;
            return $result;
        }
        elseif(is_array($this->groups) && count($this->groups)) {
            $groupsToBind = [];
            foreach($this->groups as $group) {
                $groupsToBind[] = $group->id;
            }
            $result = implode(',', $groupsToBind);
            return $result;
        }
        else return null;
    }


    public static function getStatus($status) {
        return (isset(self::$statuses[$status]) ? self::$statuses[$status] : null);
    }


    public function getCurrentStatus() {
        return (isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null);
    }


    public static function getRole($role) {
        return (isset(self::roles()[$role]) ? self::roles()[$role] : null);
    }


    public function getCurrentRole() {
        return (isset(self::roles()[$this->role]) ? self::roles()[$this->role] : null);
    }


    public static function roles() {
        return [
            self::user => 'User',
            self::promo_user => 'Promo user',
            self::admin => 'Admin',
            self::super_admin => 'Super Admin',
        ];
    }


    public static $status_colors = [
        self::STATUS_INACTIVE => 'red',
        self::STATUS_ACTIVE => 'green'
    ];


    private static $_logged_user = null;
    private static $_is_need_update = false;
    public $rememberMe;
    public $passwordConfirm;
    public $group_alias = null;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users';
    }


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'email'],
            [['email', 'password'], 'required'],
            ['email', 'unique', 'on' => ['create', 'register']],
            ['role', 'default', 'value' => self::user],
            [['passwordConfirm'], 'required', 'on' => ['create', 'register', self::PASSWORD_CHANGE_SCENARIO]],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password',
             'on' => ['create', 'register', self::PASSWORD_CHANGE_SCENARIO]],
            ['groupsToBind', 'safe'],
            ['group_alias', 'exist', 'targetClass' => Groups::className(), 'targetAttribute' => 'alias',
             'on' => 'register'],
            ['role', 'in', 'range' => array_flip(self::roles())],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['rememberMe', 'boolean'],
            [['name', 'email'], 'string', 'max' => 50],
            ['language', 'string', 'max' => 5],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 256],
        ];
    }


    public function beforeSave($insert) {
        parent::beforeSave(true);
        if($this->isNewRecord || $this->scenario == self::PASSWORD_CHANGE_SCENARIO) {
            $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        return true;
    }


    public function saveGroups() {
        UserBindings::deleteAll(['user_id' => $this->id]);
        $groups = Groups::findAll(['id' => $this->groupsToBind]);
        foreach($groups as $group) {
            if($group instanceof Groups) {
                $user_binding = new UserBindings();
                $user_binding->user_id = $this->id;
                $user_binding->group_id = $group->id;
                $user_binding->save();
            }
        }
    }


    public function afterSave($insert, $oldAttributes) {
        self::saveGroups();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('user', ':name'),
            'email' => Yii::t('user', ':email'),
            'role' => Yii::t('user', ':role'),
            'language' => Yii::t('user', ':language'),
            'groupsToBind' => Yii::t('user', ':groups'),
            'password' => Yii::t('user', ':password'),
            'passwordConfirm' => Yii::t('user', ':password_confirm'),
            'auth_key' => Yii::t('user', 'Auth Key'),
            'access_token' => Yii::t('user', 'Access Token'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBindings() {
        return $this->hasMany(UserBindings::className(), ['user_id' => 'id']);
    }


    /**@return ActiveQuery */
    public function getGroups() {
        return $this->hasMany(Groups::className(), ['id' => 'group_id'])
                    ->via('userBindings');
    }


    public function getGroupsProvider() {
        $dataProvider = new ActiveDataProvider([
                                                   'query' => Groups::find()->joinWith([
                                                                                           'users' => function ($query) {
                                                                                               $query->andFilterWhere(['users.id' => $this->id]);
                                                                                           }
                                                                                       ]),
                                                   'pagination' => [
                                                       'pageSize' => 5,
                                                   ],
                                               ]);
        return $dataProvider;
    }


    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id) {
        $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        Yii::$app->language = $user->language;
        return $user;
    }


    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be
     *     `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }


    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId() {
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
    public function getAuthKey() {
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
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }


    public static function findByEmail($email) {
        return self::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }


    public function login() {
        $user = $this->findByEmail($this->email);
        if(!$user) {
            $this->addError('email', 'Your login/password is incorrect');
            $this->addError('password', 'Your login/password is incorrect');
            return false;
        }
        if($this->scenario == 'register') {
            $this->password = $_POST[$this->formName()]['password'];
        }
        if(\Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
            $user->save();
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        else {
            $this->addError('email', 'Your login/password is incorrect');
            $this->addError('password', 'Your login/password is incorrect');
            return false;
        }
    }


    /**
     * @param bool $safe
     * @return null|Users
     * @throws NotFoundHttpException
     */
    public static function getLogged($safe = false) {
        $user = Yii::$app->user->identity;
        if($safe && !($user instanceof Users)) {
            throw new NotFoundHttpException;
        }
        return $user;
    }


    public function getEditableRoles($user_id = null) {
        $editable_roles = RbacController::getEditableRoles();
        if(isset($editable_roles[$this->role])) {
            array_walk($editable_roles[$this->role], function (&$value, $key) {
                $value = isset(self::roles()[$key]) ? self::roles()[$key] : $value;
            });
        }
        if(isset($editable_roles[$this->role])) {
            if($user_id !== null && $user_id === $this->id) {
                $editable_roles[$this->role][$this->role] = $this->getCurrentRole();
            }
        }
        return isset($editable_roles[$this->role]) ? $editable_roles[$this->role] : [];
    }


    public function canEdit($checking_role) {
        foreach(self::getEditableRoles() as $role => $label) {
            if($checking_role == $role) {
                return true;
            }
        }
        return false;
    }


    public function canDelete($checking_role) {
        return self::canEdit($checking_role);
    }


    public function canEditBeacon(Beacons $beacon) {
        $beacon_query = self::getBeaconsQuery();
        $beacon_query->andFilterWhere(['beacons.id' => $beacon->id]);
        $result = $beacon_query->one();
        return ($result instanceof Beacons);
    }


    public function getBeaconsQuery($query = null) {
        if($query == null) {
            $query = Beacons::find();
        }
        $user = $this;
        $query->joinWith([
                             'groups' => function (ActiveQuery $query) use ($user) {
                                 $query->joinWith([
                                                      'users' => function (ActiveQuery $query) use ($user) {
                                                          $query->andFilterWhere(['users.id' => $user->id]);
                                                      }
                                                  ]);
                             }
                         ]);
        return $query;
    }


    public function getBeaconPins($query = null) {
        if($query === null) {
            $query = BeaconPins::find();
        }
        $user = $this;
        $query->joinWith(['beacon' => function (ActiveQuery $query) use ($user) {
            $user->getBeaconsQuery($query);
        }]);
        return $query;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }


    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    public static function findByPasswordResetToken($token) {
        if(!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
                                   'password_reset_token' => $token,
                                   'status' => self::STATUS_ACTIVE,
                               ]);
    }


    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if(empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }


    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }
}
