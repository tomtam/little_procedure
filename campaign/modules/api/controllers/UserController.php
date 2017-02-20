<?php
namespace campaign\modules\api\controllers;

use Yii;
use campaign\models\User;
use campaign\components\Code;
use campaign\components\Wxapi;
use campaign\components\XUtils;

class UserController extends BaseController{
    public $modelClass = '';
    public function beforeAction($action){
        parent::beforeAction($action);
        return true;
    }
    /**
    * @date: 2017年1月22日 下午5:09:09
    * @author: louzhiqiang
    * @return:
    * @desc:   注册添加
    */
    public function actionRegister(){
        $userName = Yii::$app->request->post('name');
        $photoUrl = Yii::$app->request->post('photoUrl');
        $userInfo = Yii::$app->request->post('userInfo');
        
        $this->getLoginStatus(); 
        
        $model_user = new User();
        $model_user->id   = $this->userId;
        $model_user->name = $userName;
        $model_user->createTime = time();
        $model_user->photoUrl   = $photoUrl;
        $model_user->userInfo = $userInfo;
        $model_user->save();
        
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年2月19日 下午6:08:21
    * @author: louzhiqiang
    * @return:
    * @desc:   获取用户的secret和openId
    */
    public function actionSecret(){
        $code = Yii::$app->request->post('code');
        
        if(!$code){
            return Code::errorExit(Code::ERROR_PARAM_PARTIAL);
        }
        
        $res = Wxapi::getSecretId($code);
        
        $res_arr = json_decode($res, true);
        if(isset($res_arr['errcode']) && $res_arr['errcode']){
            return $res;
        }
        
        //把数据放到session里边，返回一个sessionId
        $sessionKey = md5(XUtils::getURandom());
        Yii::$app->cache->set($sessionKey,$res, 3600*24*30);
        Yii::info("---session key设置为".$sessionKey, 'api');
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $sessionKey,
        ), JSON_UNESCAPED_UNICODE);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}