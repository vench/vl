<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $role_id
 * @property string $password Description
 * @property boolean $is_active Description
 * @property string $remoteToken
 * 
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id'], 'required'],
            [['name'], 'unique'],
            [['role_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 128],
            [['phone', 'password', 'remoteToken'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }
    
    /**
     * 
     * @param string $password
     */
    public function validatePassword($password) {
        return self::createHash($password) === $this->password;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'role_id' => 'Role ID',
            'password'  => 'Password'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
    
    /**
     * 
     * @return string
     */
    public function getUserName() {
        return $this->name;
    }

    /**
     * 
     * @throws \Exception
     */
    public function getAuthKey() {
        return  self::createHash( substr( $this->password, 16) . __METHOD__ );
    }

    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param type $authKey
     * @throws \Exception
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }
    
    
    

    /**
     * 
     * @param int $id
     * @return \app\models\User
     */
    public static function findIdentity($id) {
        return self::find()->where(['is_active' => 1, 'id' => $id])->one();
    }

    /**
     * 
     * @param type $token
     * @param type $type
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \Exception("Not yet impl");
    }
    
    /**
     * 
     * @param string $username
     * @return \app\models\User
     */
    public static function findByUsername($username) {
        return self::find()->where(['name' => $username])->one();
    }
    
    
    /**
     * 
     * 
     * @param string $value
     * @return string
     */
    public static function createHash($value) {
         return md5($value . __METHOD__);
    }

    
    /**
     * 
     * @param boolean $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        
        if($this->isNewRecord && !empty($this->password) ) {
            $this->password = self::createHash($this->password);
        }
        return parent::beforeSave($insert);
    }
    
    
    
}
