<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class Evaluate extends ActiveRecord{
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_order_evaluate}}';
    }
}