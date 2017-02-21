<?php
namespace campaign\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use campaign\components\Code;

class BaseController extends ActiveController{
    protected $userId;
    protected $secret;
    public function beforeAction($action){
        $aesStr = Yii::$app->request->post('aesStr');
        if(!$aesStr){
            Yii::info("----参数缺失:缺少aesStr-----", 'api');
            exit(Code::errorExit(Code::ERROR_PARAM_PARTIAL));
        }
        $params = Yii::$app->request->post();
        if(isset($params['userInfo'])){
            $params['userInfo'] = json_decode($params['userInfo'], true);
        }
        unset($params['aesStr']);
        Yii::info("-----params:".print_r($params, true), 'api');
        if($aesStr != sha1(json_encode($params, JSON_UNESCAPED_UNICODE).Yii::$app->params['aes'])){
            Yii::info("----aesStr校验不通过:aesStr参数：".$aesStr."-----php生成的：".sha1(json_encode($params, JSON_UNESCAPED_UNICODE).Yii::$app->params['aes']), 'api');
            exit(Code::errorExit(Code::ERROR_PARAM_CHECK));
        }
        
        return true;
    }
    /**
    * @date: 2017年2月20日 下午5:17:39
    * @author: louzhiqiang
    * @return:
    * @desc:   判断是否登陆
    */
    protected function getLoginStatus(){
        if(!Yii::$app->request->post('sessionId')){
            Yii::info("---getLoginStatus cookie里无sessionId", 'api');
            exit(Code::errorExit(Code::ERROR_USER_NO_LOGIN));
        }
        $res_json = Yii::$app->cache->get(Yii::$app->request->post('sessionId'));
        if( !$res_json ){
            Yii::info("---getLoginStatus 返回结果为空", 'api');
            exit(Code::errorExit(Code::ERROR_USER_NO_LOGIN));
        }
        
        $res_arr = json_decode($res_json, true); 
        Yii::info("---getLoginStatus 用户数据：".$res_json, 'api');
        
        $this->userId = $res_arr['openid'];
        $this->secret = $res_arr['session_key'];
        return true;
    }
}