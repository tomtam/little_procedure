<?php

namespace console\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Query;
use yii\rest\ActiveController;
use \Predis\Client;
use yii\data\Pagination;

use credit\components\Api;
use credit\components\Code;

use credit\models\credit;
use credit\models\auditlog;
/**
 * Spdcrond controller
 */
header("Content-type:text/html;charset=utf-8");
class SpdcrondController extends \yii\console\Controller{//MyController {
	public $modelClass = 'credit\models\credit;';
	function init() {
		define ( "UnLogin", true );
		parent::init ();
	}
	function apiPostCurl($api_url, $data) {
		$resultData = Api::postCurl ( $api_url, $data );
		$code = Code::$login_code;
		$header = (isset($resultData['http_code'])&&$resultData['http_code']!='200')?$resultData['http_code']:'200';
		if ($header == '200') {
			$return = $resultData;
		} else {
			$error = $code ['Server_Error'] ['code'];
			$msg = $code ['Server_Error'] ['value'];
			$return = [
					'code' => $error,
					'msg' => $msg,
					'data' => ''
			];
			$return = json_encode ( $return );
		}
		return $return;
	}
	/**
	* 描述： 未激活信息短信通知彭亮
	* @date: 2016年10月20日 下午4:00:26
	* @author: lwy
	*/
	public function actionNotActive() {
		$where=[];
		$credit = new Credit();
		$result = $credit->getCreditNotActived($where,'','');
		if(isset($result)){
			Yii::info(print_r("未激活短信通知日志开始——————————————————————\n", true), 'credit');
			foreach($result as $k=>$v){
				try{
					$msg='订单号：'.$v['orderNo'].',姓名：'.$v['name'].'，身份证号：'.$v['idCard'].'未点击发卡，请核查';
					$datas=['phone'=>Yii::$app->params['monitor_phone'],'msg'=>$msg];
					echo date("Y-m-d H:i:s")."发送短信开始：".$msg."<br/>";
					self::sendMsg($datas);
				}catch (\yii\base\UnknownPropertyException $e){
					$message = $e->getMessage();
				}catch (\yii\db\Exception $e){
					$message = $e->getMessage();
				}catch (\yii\base\ErrorException $e){
					$message = $e->getMessage();
				}	
				if(isset($message)){
					Yii::info(print_r("发送短信数据异常报警 >>> ".$message. __LINE__."\n"),'credit');
					continue;
				}
				Yii::info(print_r($v['name'].'用户(id:'.$v['id'].'phone:'.$v['phone'].$v['id'].")发送短信完成 >>> ". __LINE__."\n", true),'credit');	
				sleep(3);
			}
			Yii::info(print_r("未激活短信通知日志结束——————————————————————\n", true), 'credit');	
			exit(date("Y-m-d H:i:s")." - not active send messge OK!\n");
		}else{
			exit(date("Y-m-d H:i:s")." - NO data!\n");
		}        
	}
	/**
	* 描述： 激活过期的用户批量处理结转
	* @date: 2016年10月20日 下午4:42:17
	* @author: lwy
	*/
	public function actionCheckout() {
		$where=[];
		$credit = new Credit();
		$result = $credit->getCreditCheckout($where);
		if(isset($result)){
			Yii::info(print_r("自动结转开始\n", true), 'credit');
			foreach($result as $k=>$v){
				try{
					$ret=self::chargedo($v);
					Yii::info(print_r(json_encode($ret), true),'credit');
				}catch (\yii\base\UnknownPropertyException $e){
					$message = $e->getMessage();
				}catch (\yii\db\Exception $e){
					$message = $e->getMessage();
				}catch (\yii\base\ErrorException $e){
					$message = $e->getMessage();
				}
				if(isset($message)){
					Yii::info(print_r("自动结转异常报警 >>> ".$message. __LINE__),'credit');
					continue;
				}
				Yii::info(print_r($v['name'].'用户(id:'.$v['id'].'phone:'.$v['phone'].$v['id'].")自动结转完成 >>> ". __LINE__."\n", true),'credit');
				sleep(3);
			}
			Yii::info(print_r("自动结转结束\n", true), 'credit');
			exit(date("Y-m-d H:i:s",time())." - auto checkout OK!\n");
		}else{
			exit(date("Y-m-d H:i:s",time())." - NO data!\n");
		}
	}
	public function sendMsg($datas){
		$url = Yii::$app->params['phoneMessageUrl'];
		$phone=$datas['phone'];
		$msg=$datas['msg'];
		$transId= Yii::$app->params['transId'];
		$smsSecretKey= Yii::$app->params['phoneKey'];
		$params=[
				'transId'=>$transId,
				'secret'=>md5($phone.$msg.$transId.$smsSecretKey),
				'mobile'=>$phone,
				'content'=>$msg,
		];
		
		$resultData=$this->apiPostCurl($url,$params);
		//$resultData=$this->json_decode($resultData);
		$result=[
				'code'=>$resultData['code'],
				'msg'=>$resultData['info'],
				'data'=>$resultData['data'],
		];
		return json_encode($result);
	}
	/**
	* 描述： 收取手续费
	* @date: 2016年9月12日 下午3:50:12
	* @author: lwy
	*/
	public function chargedo($data) {
		Yii::info(print_r("结转日志开始\n", true), 'credit');
		$sendParam=array();
		$updateParam=array();		
		$pay_url=Yii::$app->params['quik_pay_api'];
		
		$sendParam['tpId'] = Yii::$app->params['quik_tpId'];			
		$sendParam['orderId'] = $data['orderNo'];
		$sendParam['tradeNo'] = $data['tradeNo'];
		$sendParam['data'] = json_encode($sendParam);
		$sendParam['sign'] = md5($sendParam['data'] .Yii::$app->params['quik_privateKey']);
	    $res =  $this->apiPostCurl($pay_url.'callback/ordercarryover',$sendParam);	
		//$res=$this->json_decode($res);
		$id=$data['id'];
		$status=$data['orderStatus'];//结转
		if($res['code'] == 0){//是否需要更新状态 待确定 ？？？？
			$status='6';
			$updateParam['orderStatus'] = $status;
			$updateParam['update_time'] = date("Y-m-d H:i:s");
			Credit::setCreditOrder($updateParam,$id);
			$ret = [
					'code' => '200',
					'msg' => 'success',
			];
		}else{
			$ret = [
					'code' => $res['code'] ,
					'msg' => $res['info'],
			];
		}
		$auditlog = new Auditlog();
		$where = array(
				'audit_id'=>$id,
				'action'=>'/spdcard/chargedo',
				'flag'=>($status==6)?1:0,
				'remark'=>json_encode(array_merge($sendParam,$updateParam)),
				'type'=>'1',
				'create_time'=>time(),//date('Y-m-d H:i:s',time()),
				'examine'=>'admin',
				'status'=>$status,
		);
		$result = $auditlog->setAuditlog($where);
		Yii::info(print_r("结转日志结束\n", true), 'credit');
		return $ret;
	}

}
