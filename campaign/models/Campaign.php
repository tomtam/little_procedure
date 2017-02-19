<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;
use campaign\components\Code;

class Campaign extends ActiveRecord{
    public static $campTypeArr = array(
        '徒步','登山','跑步','越野','自由行'
    );
    public function getList($where, $page, $pageSize){
        return self::find()
                        ->where($where)
                        ->orderBy(['isStick' => SORT_DESC, 'updateTime' => SORT_DESC])
                        ->limit($pageSize)
                        ->offset(($page - 1) * $pageSize)
                        ->asArray()
                        ->all();
    }
    
    public function getCount($where){
        return self::find()
                        ->where($where)
                        ->count();
    }
    /**
    * @date: 2017年1月20日 下午3:18:19
    * @author: louzhiqiang
    * @return:
    * @desc:    查询置顶的最大数值
    */
    public function getMaxStick(){
        $sql = "select MAX(isStick) as max_stick from campaign where isDel=".Code::NOT_DEL_STATUS;
        $res = self::getDb()->createCommand($sql)->query()->read();
        return $res['max_stick'];
    }
    /**
    * @date: 2017年1月20日 下午3:49:55
    * @author: louzhiqiang
    * @return:
    * @desc:   获得来源
    */
    public function getOriginArr(){
        $res = self::find()
                        ->select(array('origin'))
                        ->distinct('origin')
                        ->where(['isDel' => Code::NOT_DEL_STATUS])
                        ->asArray()
                        ->all();
        return $res;
    }
    /**
    * @date: 2017年1月21日 下午9:25:15
    * @author: louzhiqiang
    * @return:
    * @desc:   获得所有地区的值。
    */
    public function getLocationName(){
        $sql = "select count(locationName) as count,locationName from campaign where isDel=".Code::NOT_DEL_STATUS." group by locationName";
        
        return self::getDb()->createCommand($sql)->queryAll();
    }
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign}}';
    }
}