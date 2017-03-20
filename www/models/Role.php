<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_role".
 *
 * @property integer $id
 * @property string $title
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    
    const ROLE_TYPE_ADMIN = 1;
    
    const ROLE_TYPE_USER = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role_id' => 'id']);
    }
    
    /**
     * 
     * @return boolean
     */
    public static function currentUserIsAdmin() {
        $user = self::currentUser();
        return !is_null($user) && $user->role_id == self::ROLE_TYPE_ADMIN;
    }
    
    /**
     * 
     * @return User
     */
    public static function currentUser() {
        if(!Yii::$app->user->isGuest && !is_null($user = User::findIdentity(Yii::$app->user->id))) {
            return $user;
        }
        return null;
    }
}
