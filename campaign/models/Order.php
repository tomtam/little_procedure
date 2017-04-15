<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord{
    /**
     * 付款完成
     * @var unknown
     */
    const STATUS_ORDER_PAY_SUCCESS = 1;
    const STATUS_ORDER_PAY_FAIL = 2;
    const STATUS_ORDER_PAY_UN = 3;
    /**
     * 活动进行中
     * @var unknown
     */
    const STATUS_ORDER_CAMP_ING = 2;
    /**
     * 活动已结束
     * @var unknown
     */
    const STATUS_ORDER_CAMP_OVER = 3;
    
    static $arr_order_status = array(
        self::STATUS_ORDER_PAY_SUCCESS => '付款成功',
        self::STATUS_ORDER_CAMP_ING    => '活动进行中',
        self::STATUS_ORDER_CAMP_OVER   => '活动已结束',
    );
    /**
     * 是否已经评价过啦
     * @var unknown
     */
    const EVALUATE_ORDER_DONE = 1;
    const EVALUATE_ORDER_NO   = 0;
    
    static $arr_order_evaluate = array(
        self::EVALUATE_ORDER_DONE => '已评价',
        self::EVALUATE_ORDER_NO   => '去评价',
    );
    /**
    * @date: 2017年1月21日 下午9:42:05
    * @author: louzhiqiang
    * @return:
    * @desc:   根据camp信息计算出状态值
    */
    public static function processStatus($campInfo){
        //当前时间大于活动开始时间   活动进行中
        //当前时间大于活动结束时间   活动已结束
        if(time() > $campInfo['beginTime'] && time() < $campInfo['endTime']){
            return self::STATUS_ORDER_CAMP_ING;
        }elseif(time() > $campInfo['endTime']){
            return self::STATUS_ORDER_CAMP_OVER;
        }else{
            return self::STATUS_ORDER_PAY_SUCCESS;
        }
    }
    /**
    * @date: 2017年1月22日 上午11:17:26
    * @author: louzhiqiang
    * @return:
    * @desc:   更新订单列表里的title
    */
    public function updateCampTitle($title, $campId){
        $sql = "update campaign_order set campTitle='{$title}' where campId=".$campId;
        $raw = self::getDb()->createCommand($sql)->execute();
        if(!$raw){
            Yii::info("-----更新活动数据来更新order列表里的title数据：".$sql, 'camp');
        }
        return $raw;
    }
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_order}}';
    }
}