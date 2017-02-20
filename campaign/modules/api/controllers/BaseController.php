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
        unset($params['aesStr']);
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
        $res_json = Yii::$app->cache->get(Yii::$app->request->cookies['sessionId']);
        if( !$res_json ){
            exit(Code::errorExit(Code::ERROR_USER_NO_LOGIN));
        }
        
        $res_arr = json_decode($res_json, true); 
        
        $this->userId = $res_arr['openid'];
        $this->secret = $res_arr['session_key'];
        return true;
    }
}