<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;
use campaign\components\Code;

class Share extends ActiveRecord{
    const SHARE_TYPE_IMG = 2;
    const SHARE_TYPE_VIDEO = 1;
    
    public static $arrShareType = array(
        self::SHARE_TYPE_IMG => '图片',
        self::SHARE_TYPE_VIDEO => '视频',
    );
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_share}}';
    }
    /**
     * @date: 2017年1月20日 下午3:18:19
     * @author: louzhiqiang
     * @return:
     * @desc:    查询置顶的最大数值
     */
    public function getMaxStick(){
        $sql = "select MAX(isStick) as max_stick from campaign_share where isDel=".Code::NOT_DEL_STATUS;
        $res = self::getDb()->createCommand($sql)->query()->read();
        return $res['max_stick'];
    }
}