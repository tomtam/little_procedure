<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;
use campaign\components\Code;
use campaign\models\Search;

class Campaign extends ActiveRecord{
    public static $campTypeArr = array(
        '徒步', '钓鱼', '户外摄影', '骑行', '露营', '冲浪', '帆船', '漂流', '观鸟', '踏青', '越野跑', '马拉松', '向导', '进藏', '登山'
    );
    public static $campLocationNameArr = array(
        '北京', '云南', '河北', '江苏', '西藏', '海南', '安徽', '江西', '贵州', '福建', '日本', '美国', '俄罗斯', '加拿大', '南北极', '四川'
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
    /**
      * 根据关键字去反向匹配需要的字符串
      */
    public function getIdByKeyword($keyword){
	$arrKeyword = Search::find()->where(['fieldName'=>Search::FIELD_KEYWORD])->asArray()->all();
	foreach($arrKeyword as $val){
	    if(strpos($keyword, $val['content']) !== false){
		$arr[$val['campId']]++;
	    }
	}
	return $arr;
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
