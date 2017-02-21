<?php

namespace campaign\components;

/*
 * 全局错误码
 */
class Code {
	const SUCC = 200;
	const ERROR_USER_INFO = '1001';
	const ERROR_USER_NO_LOGIN = '1002';
	
	const ERROR_CAMP_INSERT = '2001';
	const ERROR_CAMP_UPDATE = '2002';
	const ERROR_CAMP_DELETE = '2003';
	const ERROR_CAMP_STICK  = '2004';
	const ERROR_CAMP_CANCEL_STICK  = '2005';
	
	const ERROR_IMAGE_UPLOAD  = '3001';
	const ERROR_IMAGE_DEL     = '3002';
	
	const ERROR_ORDER_CREATE  = '4001';
	const ERROR_ORDER_CAMPNUM  = '4002';
	const ERROR_ORDER_NUM  = '4003';
	
	const ERROR_VERIFY_CHECK = "5001";
	
	const ERROR_PARAM_PARTIAL = "6001";
	const ERROR_PARAM_CHECK = "7001";
	
	public static $arr_code_status = array(
	    self::SUCC => '操作成功',
	    self::ERROR_USER_INFO => '用户信息错误',
	    self::ERROR_USER_NO_LOGIN => '用户未登录',
	    
	    self::ERROR_CAMP_INSERT => '插入失败',
	    self::ERROR_CAMP_UPDATE => '更新失败',
	    self::ERROR_CAMP_DELETE => '删除失败',
	    self::ERROR_CAMP_STICK  => '置顶失败',
	    self::ERROR_CAMP_CANCEL_STICK => '取消置顶失败',
	    
	    self::ERROR_IMAGE_UPLOAD => '上传图片失败',
	    self::ERROR_IMAGE_DEL    => '删除图片失败',
	    
	    self::ERROR_ORDER_CREATE => '订单添加失败',
	    self::ERROR_ORDER_CAMPNUM => '订单数量过大',
	    self::ERROR_ORDER_NUM    => '订单数目错误',
	    
	    self::ERROR_VERIFY_CHECK => '验证码错误',
	    
	    self::ERROR_PARAM_PARTIAL => '参数缺失',
	    self::ERROR_PARAM_CHECK   => '参数校验失败',
	);
	
	const DEL_STATUS = 1;
	const NOT_DEL_STATUS = 0;
	
	const STR_SEPARATOR = "|";
	/**
	* @date: 2016年12月19日 下午2:20:39
	* @author: louzhiqiang
	* @return:
	* @desc:   返回错误信息
	*/
	public static function errorExit($code, $message = NULL){
        return json_encode(array(
    	    'code' => $code,
    	    'info' => $message ? $message : Code::$arr_code_status[$code],       
	   ), JSON_UNESCAPED_UNICODE);	    
	}
}
