<?php
    namespace app\models;

    use dezmont765\yii2bundle\models\MainActiveRecord;
    use Yii;
    use yii\db\ActiveQuery;

    /**
     * This is the model class for table "client_users".
     *
     * @property integer $id
     * @property string $email
     * @property string $password
     * @property string $auth_key
     * @property string $group_ids
     */
    class ClientUsers extends MainActiveRecord
    {


        const FB_AUTH_SCENARIO = 'fb_auth_scenario';
        const PASSWORD_CHANGE_SCENARIO = 'password_change_scenario';


        /**
         * @inheritdoc
         */
        public static function tableName() {
            return 'client_users';
        }


        /**
         * @inheritdoc
         */
        public function rules() {
            return [
                [['email', 'password', 'auth_key', 'fb_identifier'], 'string', 'max' => 256],
                ['password','required','on'=>self::PASSWORD_CHANGE_SCENARIO],
                ['group_ids', 'safe'],
                ['email', 'email'],
                ['email', 'unique']
            ];
        }


        /**
         * @inheritdoc
         */
        public function attributeLabels() {
            return [
                'id' => Yii::t('app', 'ID'),
                'email' => Yii::t('app', 'Email'),
                'password' => Yii::t('app', 'Password'),
                'auth_key' => Yii::t('app', 'Auth Key'),
            ];
        }


        public function beforeSave($insert) {
            parent::beforeSave(true);
            if($this->isNewRecord || $this->scenario == self::PASSWORD_CHANGE_SCENARIO) {
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                if(empty($this->auth_key)) {
                    $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
                }
            }
            return true;
        }


        public function login() {
            $client_user = ClientUsers::findOne(['email' => $this->email]);
            if($client_user instanceof ClientUsers) {
                if(!Yii::$app->getSecurity()->validatePassword($this->password, $client_user->password)) {
                    $this->addError('password', 'Your password is invalid');
                    return false;
                }
                else {
                    $this->auth_key = $client_user->auth_key;
                    return true;
                }
            }
            else return false;
        }


        public function fbAuth() {
            $client_user = ClientUsers::findByEmail($this->email);
            if($client_user instanceof ClientUsers) {
                $client_user->auth_key;
            }
            else {
                $client_user = new ClientUsers();
                $client_user->attributes = $this->attributes;
            }
            $client_user->save();
            $this->auth_key = $client_user->auth_key;
            return true;
        }


        public function getGroupIds() {
            $return = json_decode($this->group_ids, true);
            return $return;
        }


        public function generateNewPassword() {
            $this->password = Yii::$app->security->generateRandomString(6);
            return $this->password;
        }


        public function sendPasswordRestoreEmail() {
            $user = ClientUsers::findOne([
                                       'email' => $this->email,
                                   ]);
            if($user instanceof ClientUsers) {
                $password = $user->generateNewPassword();
                $user->scenario = self::PASSWORD_CHANGE_SCENARIO;
                if($user->save()) {
                    return \Yii::$app->mailer->compose('new-password', [
                        'email' => $user->email,
                        'password' => $password,
                    ])
                        ->setTo($this->email)
                        ->setSubject('Password restore')
                        ->send();
                }
            }
            return false;
        }


        public static function findByEmail($email) {
            return self::findOne(['email' => $email]);
        }
    }
