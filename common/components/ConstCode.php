<?php
namespace common\components;

class ConstCode{
    const SUCCESS = 0;
    
    const ERROR_CODE_SIGN_FAILED       = 3001;
    const ERROR_CODE_SIGN_VERIFY_FAILED       = 3002;
    
    const ERROR_CODE_HTTP_RETURN_FALSE = 4001;//接口访问异常
    
    const ERROR_CODE_PARAM_PARTIAL     = 5001;//缺少参数的错误
    const ERROR_CODE_ORDER_AMOUNT     = 5002;//购买金额不是100的整数倍
    const ERROR_CODE_ORDER_EXCESS     = 5003;//超额
    
    const ERROR_CODE_RESULT_EXCEPTION  = 7001;//异常：返回结果为空
    
    const PARAMERROR = 6000;
    const BANKNOERROR = 6001;
    const IDCARDERROR = 6002;
    const PHONENOERROR = 6003;
    const CARDTYPEERROR = 6004;
    const IDCARDTYPEERROR = 6005;
    const BINDFAIL = 6006;//绑卡失败
    const BINDDBEXP = 6007;//绑卡入库异常
	
    const BINDINFONOTEXISTS = 6008;
    const TIMEAMOUNTERROR = 6009;
    const DAYAMOUNTERROR = 6010;
    const CREATEORDERFAIL = 6011;
    const PROJECTEXPIRE = 6020;//
	const BANKNOTSUPPORT = 6030;
    
    public static $arr_err_status = array(
        self::SUCCESS                      => 'success',
        self::ERROR_CODE_SIGN_FAILED       => '签名失败',
        self::ERROR_CODE_SIGN_VERIFY_FAILED=> '验签失败',
        self::ERROR_CODE_HTTP_RETURN_FALSE => '接口返回信息异常',
        self::ERROR_CODE_PARAM_PARTIAL     => '参数不全',
        self::ERROR_CODE_RESULT_EXCEPTION  => '返回结果异常',
        self::ERROR_CODE_ORDER_AMOUNT      => '购买金额有误',
		self::ERROR_CODE_ORDER_EXCESS      => '超出剩余购买金额',
    		
    	self::PARAMERROR 				   => '请求参数非法',
    	self::BANKNOERROR 				   => '银行卡号有误',
    	self::IDCARDERROR 				   => '身份证号码有误',
    	self::PHONENOERROR 				   => '手机号格式错误',
    	self::CARDTYPEERROR 			   => '用户注册类型非法',// 11  12
    	self::IDCARDTYPEERROR 			   => '证件类型有误',
    	self::BINDFAIL 					   => '绑卡失败',
    	self::BINDDBEXP 				   => '您已绑定过该卡',
    		
    	self::BINDINFONOTEXISTS 	       => '绑卡流水号不存在',
    	self::TIMEAMOUNTERROR 			   => '单笔限额超出范围',
    	self::DAYAMOUNTERROR 			   => '单日限额超出范围',
    	self::CREATEORDERFAIL 			   => '创建订单失败',
    	self::PROJECTEXPIRE                => '项目已过期',	
    	self::BANKNOTSUPPORT               => '不支持绑定该卡号所在的银行',
    );
    
    const PAGESIZE = 10;//每页
    const VALUE_APPRENCE_REQUIRE = "R";//参数要求  必须出现
    const VALUE_APPRENCE_OPTIONAL = "O";//参数要求  选择出现
    const VALUE_APPRENCE_CONDITIONAL = "C";//参数要求  有条件出现
    
    //日志级别
    const LOG_LEVEL_ERROR = "error";
    const LOG_LEVEL_TRACE = "trace";
    const LOG_LEVEL_WARNING = "warning";
    const LOG_LEVEL_INFO = "info";
    
    //来源类型
    const FROM_DEV_ANDROID = 1;
    const FROM_DEV_IOS = 2;
    const FROM_DEV_PC  = 3;
    const FROM_DEV_OTHER = 4;
    
    public static $arr_from_dev = array(
        self::FROM_DEV_ANDROID => '安卓',
        self::FROM_DEV_IOS     => '苹果',
        self::FROM_DEV_PC      => 'pc',
        self::FROM_DEV_OTHER   => '其他',
    );
    
    //常用的数据库状态
    const IS_DEL = 1;
    const NOT_DEL = 0;
    
    //支付结果回调返回状态
    const PAY_RESULT_SUCC = 100;
    const PAY_RESULT_FAIL = 101;
    const PAY_RESULT_ORDERNO_NOTEXISTS = 102;
    const PAY_RESULT_ORDERNO_ALREADYCHANGED = 103;
    const PAY_RESULT_SIGN_FAIL = 104;
    const PAY_RESULT_EMPTY = 105;
	public static $arr_pay_result = [
    		self::PAY_RESULT_SUCC => '支付结果回调成功',
    		self::PAY_RESULT_FAIL => '支付结果回调失败',
    		self::PAY_RESULT_ORDERNO_NOTEXISTS => '支付结果回调失败，订单号不存在',
    		self::PAY_RESULT_ORDERNO_ALREADYCHANGED=>'重复回调，该订单已经支付成功',
			self::PAY_RESULT_SIGN_FAIL => '支付结果回调验签失败',
			self::PAY_RESULT_EMPTY => '支付结果回调请求参数解析错误',
    ];
    //还款通知的回调返还状态
    const REPAYMENT_SUCC = 100;
    const REPAYMENT_FAIL = 101;
    const REPAYMENT_PROJECTID_NOTEXISTS = 102;
    const REPAYMENT_ALREADYCHANGED = 103;
    const REPAYMENT_SIGN_FAIL = 104;
    const REPAYMENT_EMPTY = 105;
    public static $arr_repayment = [
    		self::REPAYMENT_SUCC => '还款通知回调成功',
    		self::REPAYMENT_FAIL => '还款通知回调失败',
    		self::REPAYMENT_PROJECTID_NOTEXISTS => '还款通知回调失败，项目Id不存在',
    		self::REPAYMENT_ALREADYCHANGED => '重复回调，该订单已经完成还款',
    		self::REPAYMENT_SIGN_FAIL => '还款通知回调验签失败',
    		self::REPAYMENT_EMPTY => '还款通知回调请求参数解析错误',
    ];    
    
}