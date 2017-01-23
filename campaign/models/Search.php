<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class Search extends ActiveRecord{
    /**
     * 种类
     * @var unknown
     */
    const FIELD_TYPE = "campType";
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_search}}';
    }
}