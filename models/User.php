<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{

    public $repeat_password;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'max' => 20,'min'=>6],
            [['username', 'repeat_password'], 'required', 'on'=>'insert'],
            [['username', 'repeat_password'], 'string', 'min'=>6, 'max'=>20],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return new static(User::findOne($id));
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return new static(User::findOne(['access_token' => $token]));
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if ($user = static::findOne(['username' => $username]) )
            return new static($user);

        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function checkPasses(){
//        $this->addError('repeat_password', 'Incorrect username or password.');
        return $this->password === $this->repeat_password;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->access_token = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
}
