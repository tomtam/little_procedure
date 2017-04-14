<?php
namespace campaign\controllers;

use Yii;
use yii\web\Controller;
use campaign\models\User;

class BaseController extends Controller{
    protected $username;
    public function beforeAction($action){
	//判断是不是手机端
 	if($this->is_mobile() && !strpos($_SERVER['REQUEST_URI'], 'api')){
		$this->redirect("/m");
		Yii::$app->response->send();
	}
        //判断是否登陆
        if( !Yii::$app->session[User::USER_LOGIN_STATUS_KEY] ){
            $this->redirect("/login");
            Yii::$app->response->send();
        }
        $this->username = User::USER_ADMIN_USERNAME;
        return true;
    }
    function is_mobile() {
	$user_agent = $_SERVER ['HTTP_USER_AGENT'];
	$mobile_browser = Array (
        	"mqqbrowser", // 手机QQ浏览器
      		"opera mobi", // 手机opera
      		"juc",
      		"iuc", // uc浏览器
      		"fennec",
      		"ios",
      		"applewebKit/420",
      		"applewebkit/525",
      		"applewebkit/532",
      		"ipad",
      		"iphone",
      		"ipaq",
      		"ipod",
      		"iemobile",
      		"windows ce", // windows phone
      		"240×320",
      		"480×640",
      		"acer",
      		"android",
      		"anywhereyougo.com",
      		"asus",
      		"audio",
      		"blackberry",
      		"blazer",
      		"coolpad",
      		"dopod",
      		"etouch",
      		"hitachi",
      		"htc",
      		"huawei",
      		"jbrowser",
      		"lenovo",
      		"lg",
      		"lg-",
      		"lge-",
      		"lge",
      		"mobi",
      		"moto",
      		"nokia",
      		"phone",
      		"samsung",
      		"sony",
      		"symbian",
      		"tablet",
      		"tianyu",
      		"wap",
      		"xda",
      		"xde",
      		"zte"
  	);
  	$is_mobile = false;
  	foreach ( $mobile_browser as $device ) {
    		if (stristr ( $user_agent, $device )) {
      		$is_mobile = true;
     		 break;
    		}	
  	}
  	return $is_mobile;
	}
}
