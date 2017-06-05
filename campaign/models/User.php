<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord{
    const USER_ADMIN_USERNAME = "admin";
    
    const USER_ADMIN_PASSWORD = "li450611fuxing";
    
    const USER_LOGIN_STATUS_KEY = "user_login_status";
    
    const USER_SOURCE_WX = 1;
    const USER_SOURCE_WAP = 2;
    const USER_SOURCE_PC = 3;
    
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_user}}';
    }
}
