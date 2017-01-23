<?php
/*
* author: gaohl
*
* 2016-6-24
* 基金工具类
*/
namespace common\components;
use Yii;
use common\components\wjk\BusinessType;

if (!defined('ROOT')) {
    define('ROOT', dirname(__FILE__) . '/');
}
class Common {

    Const CERT_DIR = ROOT;

	static function curlGet($url, $data) {
        Yii::info('-----------------------------------请求开始'."\n".print_r($data, true), 'api');

        $query_string = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url."?".$query_string);
        // dprint($url.$query_string);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $res = curl_exec($ch);
        $logData = [
            'httpcode'=> curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'url'=>$url."?".$query_string,
            'return'=>$res,
        ];
        curl_close($ch);
        Yii::info('-----------------------------------请求结束'."\n".print_r($logData, true), 'api');
        return $res;
    }
    /**
     * curl POST
     *
     * @param   string  url
     * @param   array   数据
     * @param   int     请求超时时间
     * @param   bool    HTTPS时是否进行严格认证
     * @return  string
     */
    static function curlPost($url, $data = array(), $timeout = 30, $header = array()){
        Yii::info('-----------------------------------请求开始'."\n".print_r($data, true), 'api');
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(count($header)){
            $header = array_merge(array('Expect:'), $header);
        }else{
            $header = array('Expect:');
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //避免data数据过长问题
        curl_setopt($ch, CURLOPT_POST, true);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //data with URLEncode
        curl_setopt ($ch, CURLOPT_USERAGENT, "devType=windows;");
        $ret = curl_exec($ch);
        $logData = [
            'httpcode'=> curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'url'=>$url,
            'error' => curl_error($ch),
            'return'=>$ret,
        ];
    
        curl_close($ch);
        Yii::info('-----------------------------------请求结束'."\n".print_r($logData, true), 'api');
        return $ret;
    }
    /**
    * @date: 2016年12月6日 下午2:32:18
    * @author: louzhiqiang
    * @return:
    * @desc:   以json形式返回数据
    */
    public static function exit_json($code, $data = array(), $msg=''){
    	$arrData = array(
            'code'=>$code, 
    	    'data'=>$data,
            'info'=>$msg , 
    	);
    	Yii::info("返回的数据为:".print_r($arrData,true)."\r\n", 'api');
        exit(stripslashes(json_encode($arrData,JSON_UNESCAPED_UNICODE)));
    }
    /**
     * 签名 使用私钥加签
     * return string
     * $plainText 原文
     */
	static function sign($plainText) {
        $priv_key = file_get_contents(dirname(__FILE__) . '/'.Yii::$app->params['wjk_cert_path_pfx']); //BusinessType::PRIVATE_KEY 获取密钥文件内容  
		openssl_pkcs12_read($priv_key, $certs, Yii::$app->params['wjk_cert_pw']); //读取公钥、私钥    
        $prikeyid = $certs['pkey']; //私钥   
        $result_sign = openssl_sign($plainText, $signMsg, $prikeyid,OPENSSL_ALGO_SHA1); //注册生成加密信息    
        if(!$result_sign){
            Yii::info("--------------sign failed------------and error msg is:\n". openssl_error_string());
            return false;
        }
        $signMsg = base64_encode($signMsg); //base64转码加密信息 
        return $signMsg;
	}
	/**
     * 验签 使用公钥验签 
     * return boolean
     * $plainText  原文
     * $signature  验签串
     */
	static function verify($signData) {
        $priv_key = file_get_contents(dirname(__FILE__) . '/'.Yii::$app->params['wjk_cert_path_pem']); //获取密钥文件内容
		$unsignMsg = base64_decode($signData['data']);//base64解码加密信息 
        //openssl_pkcs12_read($priv_key, $certs, BusinessType::PRIVATE_KEY_PASS); //读取公钥、私钥    
        $pubkeyid = openssl_pkey_get_public($priv_key); //公钥    
        $res = openssl_verify($unsignMsg,base64_decode($signData['signdata']),$pubkeyid); //验证    
        return $res ? true : false; //输出验证结果，1：验证成功，0：验证失败  
	}
	/**
	 * url过来的空格转化为+
	 * return string
	 * $str 原始字符串
	 */
	public static function spaceToAdd($str) {
		return preg_replace("/\s/", "+", $str);
	}
	/**
	 * 发送短信接口
	 */
	public static function sendMessage($phone,$msg) {
		$url = Yii::$app->params['phoneMessageUrl'];
		$transId = Yii::$app->params['transId'];
		$key = Yii::$app->params['phoneKey'];
		$params=[
				'transId'=>$transId,//定义死的参数，只要是固定值就可以
				'secret'=>md5($phone.$msg.$transId.$key),
				'mobile'=>$phone,
				'content'=>$msg,
		];
		 
		$resultData = self::curlPost($url,$params);
		$resultData = json_decode($resultData,true);
		//echo $phone;echo $msg;dprint($resultData);
		return $resultData['code'] == 0 ? true : false;
	}
	/**
	 * 短信模板替换
	 */
	public static function formatMsg($msg,$msgArr,$replaceArr) {
		return str_replace($msgArr, $replaceArr, $msg);
	}
}