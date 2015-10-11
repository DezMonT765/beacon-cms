<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "client_users".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $fb_identifier
 */
class ClientUsers extends MainActiveRecord
{


    const FB_AUTH_SCENARIO = 'fb_auth_scenario';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fb_identifier','required','on'=>self::FB_AUTH_SCENARIO],
            [['email', 'password', 'auth_key','fb_identifier'], 'string', 'max' => 256],
            ['email','email'],
            ['email','unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave(true);
        if ($this->isNewRecord) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
        }

        return true;
    }

    public function  login() {
        $client_user = ClientUsers::findOne(['email'=>$this->email]);
        if($client_user instanceof ClientUsers) {
            if(!Yii::$app->getSecurity()->validatePassword($this->password, $client_user->password)) {
                $this->addError('password','Your password is invalid');
                return false;
            }
            else
            {
                $this->auth_key = $client_user->auth_key;
                return true;
            }
        } else return false;

    }

    public function fbAuth() {
        $client_user = ClientUsers::findByEmail($this->email);
        if($client_user instanceof ClientUsers)
        {
            if(empty($client_user->fb_identifier))
            {
                $client_user->fb_identifier = $this->fb_identifier;
            }
            else
            {
                if($client_user->fb_identifier !== $this->fb_identifier)
                {
                    $this->addError('password','Your identifier is invalid');
                    return false;
                }
            }
        }
        else {
            $client_user = new ClientUsers();
            $client_user->attributes = $this->attributes;
        }
        $client_user->scenario = self::FB_AUTH_SCENARIO;
        $client_user->save();
        $this->auth_key = $client_user->auth_key;
        return true;
    }




    public static  function findByEmail($email)
    {
        return self::findOne(['email'=>$email]);
    }
}
