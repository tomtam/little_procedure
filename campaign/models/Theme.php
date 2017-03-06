<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;
use campaign\components\Code;

class Theme extends ActiveRecord{
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_theme}}';
    }
    /**
     * @date: 2017年1月20日 下午3:18:19
     * @author: louzhiqiang
     * @return:
     * @desc:    查询置顶的最大数值
     */
    public function getMaxStick(){
        $sql = "select MAX(isStick) as max_stick from campaign_theme where isDel=".Code::NOT_DEL_STATUS;
        $res = self::getDb()->createCommand($sql)->query()->read();
        return $res['max_stick'];
    }
}