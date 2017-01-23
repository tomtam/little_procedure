<?php
return [
    //新版info接口
    'info_api' => 'http://10.12.4.79:8080/',
	//实名认证接口
	'ehome_personal_api' => 'http://10.12.4.12:9091/ehomepay_usercenter/',
    //老接口   用户中心
    'lft_core_personal_api'  => 'http://10.12.4.12:8080/ehome_personal/',
    //用户中心
    'userCenter_api' => 'http://10.12.4.61/ehomepay-passport/',
    //数字证书  老接口
    'lft_cert_old_api' =>  'http://10.12.4.19:8080/ehomepay_cert/',
    //财富接口
    'finance_api' => 'http://10.12.4.48:89/',
    //发送短信验证码
    'lft_sms_api' => 'http://10.12.4.75:8080/smsplatform/send/message',
    //图片中心  获取图片token的时候会用到
    'image_api' => 'http://10.12.4.74:8080/',
];