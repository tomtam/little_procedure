<?php
namespace campaign\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use campaign\components\Code;

class BaseController extends ActiveController{
    public function beforeAction($action){
        $aesStr = Yii::$app->request->post('aesStr');
        if(!$aesStr){
            exit(Code::errorExit(Code::ERROR_PARAM_PARTIAL));
        }
        $params = Yii::$app->request->post();
        unset($params['aesStr']);
        if($aesStr != sha1(json_encode($params, JSON_UNESCAPED_UNICODE).Yii::$app->params['aes'])){
            exit(Code::errorExit(Code::ERROR_PARAM_CHECK));
        }
        return true;
    }
}