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
            [['username', 'password','email'], 'required'],
            [['username', 'password'], 'string', 'max' => 20,'min'=>6],
            [['username', 'email', 'password','repeat_password'], 'required', 'on'=>'insert'],
            [['username', 'repeat_password'], 'string', 'min'=>6, 'max'=>20],
            [['email'], 'string', 'min'=>6, 'max'=>255],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords mismatch" ],
            [['email','username'], 'unique'],
            ['email', 'match', 'pattern' => '/^[a-zA-Z0-9.!#$%&’*+^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/i'],
            ['name','string','max'=>255]

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

    /**
     * Method bofore save
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->password = md5($this->password);
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->access_token = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
}
