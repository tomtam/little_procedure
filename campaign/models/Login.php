<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class Login extends ActiveRecord{
    const VERIFY_CODE_SESSION_KEY = "verify_code_session_login";
    
    const USERNAME_SESSION = "username_login";
    
    const PHONE_CODE_SESSION = 'phone_code';
    
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_user}}';
    }
}