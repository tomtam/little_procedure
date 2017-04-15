<?php
namespace campaign\modules\wap\controllers;

use Yii;
use yii\rest\ActiveController;
use campaign\components\Code;
use campaign\models\User;

class BaseController extends ActiveController{
    protected $userId;
    protected $secret;
    public function beforeAction($action){
        return true;
    }
    /**
    * @date: 2017年2月20日 下午5:17:39
    * @author: louzhiqiang
    * @return:
    * @desc:   判断是否登陆
    */
    protected function getLoginStatus(){
        if(!is_string(Yii::$app->session[User::USER_LOGIN_STATUS_KEY]) || strlen(Yii::$app->session[User::USER_LOGIN_STATUS_KEY])<20  || !Yii::$app->session[User::USER_LOGIN_STATUS_KEY]){
            Yii::info("---getLoginStatus cookie里无sessionId", 'api');
            exit(Code::errorExit(Code::ERROR_USER_NO_LOGIN));
        }
        $this->userId = Yii::$app->session[User::USER_LOGIN_STATUS_KEY];
        return true;
    }
}
