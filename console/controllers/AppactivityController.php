<?php
namespace console\controllers;

use Yii;
use console\models\Bank;
use common\components\XUtils;
use common\components\Api;
use yii\db\Exception;

class AppactivityController extends \yii\console\Controller{
	
	public $amountType = [
		1=>188,
		2=>88,
		3=>58,
		4=>18,
		5=>8
	];
	public $limitDate = [
        'start'=>'2016-08-10 12:00:00',
        'end'=>'2016-09-08 23:59:59',
    ];
	public function actionRechange(){
		
		set_time_limit(0);
		if(time() < strtotime($this->limitDate['start']) || time() > strtotime($this->limitDate['end'])){
            exit(date('Y-m-d H:i:s').'不在游戏时间'."\n");
        }
		$db = Yii::$app->db_sso;
		$payRows = $db->createCommand('SELECT * from p_activity where pay_res = 0')
		->queryAll();
		foreach((array)$payRows as $info){
			$postData = array(
                        'userId' => $info['uid'],
                        'ip'     => XUtils::getClientIP(),
                        'amount' => $this->amountType[$info['level']],
                        'sn'     => substr(md5($info['uid'].time()),8,16),
			);
			Yii::info('请求充值参数'.print_r($postData,true));
			$res = Api::postCurl(Yii::$app->params['lft_personal'].'fund/transforProfitByFund/',$postData);
			$resData = json_decode($res,true);
			Yii::info('请求充值返回'.print_r($resData,true));
			if(isset($resData['result'])){
				$date = date('Y-m-d H:i:s');
				$sql = "update p_activity set pay_res = '{$resData['result']}' ,res_msg='{$resData['resultMessage']}', updatetime = '{$date}' where id = {$info['id']}";
				Yii::info('更新sql->'.$sql);
				$db->createCommand($sql)->execute();
			}else{
				Yii::info('请求接口报错');
			}
		}
		exit(date('Y-m-d H:i:s').'执行'."\n");
	}
   
}
