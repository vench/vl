<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * 
     * @param string $condition
     * @param array $params
     * @return \static
     */
    public static function findByCondition($condition, $params = []) {
        $model = UserDb::find()->andWhere($condition, $params)->asArray()->one();
        if(!empty($model)) {
            return new static($model);
        } 
         
        return null;
    }
   

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {        
        return self::findByCondition('id=:id', [':id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {  
        return self::findByCondition('accessToken=:token', [':token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
       return self::findByCondition('username=:username', [':username' => $username]);
    }
    
    
    /**
     * 
     * @param string $value
     * @return string
     */
    public static function passwordHash($value) {
        return password_hash($value, PASSWORD_DEFAULT, [
            'salt'  => __METHOD__,
        ]);
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
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user 
     */
    public function validatePassword($password)
    {
        return $this->password === \app\models\User::passwordHash( $password );
    }
}
