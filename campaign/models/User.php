<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord{
    const USER_ADMIN_USERNAME = "admin";
    
    const USER_ADMIN_PASSWORD = "123456";
    
    const USER_LOGIN_STATUS_KEY = "user_login_status";
    
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_user}}';
    }
}