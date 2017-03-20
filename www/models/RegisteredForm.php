<?php
 

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Description of RegisteredForm
 *
 * @author vench
 */
class RegisteredForm extends Model {
 
    public $username;
    public $password;  
    public $email; 
    public $phone; 
    public $verifyCode;
    
     /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
           
            [['username', 'password', 'email', 'phone'], 'required'],
            [['email'], 'email'],
            [['username'], 'validateUserName'],
            
             ['verifyCode', 'captcha'],
        ];
    }
    
    /**
     * 
     * @param type $attribute
     * @param type $params
     */
    public function validateUserName($attribute, $params) {
        $user =  User::findByUsername($this->username);
        if(!is_null($user)) {
            $this->addError($attribute, "User [{$this->username}] already exists");
        }
    }
    
    /**
     * 
     * @return \app\models\User
     */
    public function createUser() {
        
        
        $user = new User();
        $user->name = $this->username;
        $user->password = $this->password;
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->role_id = Role::ROLE_TYPE_USER;
        $user->remoteToken = User::createHash($this->email . time());
        $user->is_active = false;
        
        if($user->save() ) {
            
            $url = \yii\helpers\Url::toRoute(['site/active', 'token' => $user->remoteToken ], true);
            $body =<<<BODY
                   <html><head></head><body>
                   <a href="{$url}">Go to active user!</a>
   </body><html>     
  
BODY;
                    
            
            Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setFrom(['admin@site.test' => Yii::$app->name])
                ->setSubject('Register form')
                ->setHtmlBody($body) 
                ->send();
        }
        
        
        
        
        
        return $user;
    }
}

