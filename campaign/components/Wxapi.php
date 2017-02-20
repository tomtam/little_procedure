<?php
namespace campaign\components;

use Yii;
use common\components\Common;

class Wxapi{
    
    const secretApi = "https://api.weixin.qq.com/sns/jscode2session?appid={__APPID__}&secret={__SECRET__}&js_code={__JSCODE__}&grant_type=authorization_code";
    
    public static function getSecretId($code){
        $url = str_replace("{__JSCODE__}", $code, self::secretApi);
        $url = str_replace("{__APPID__}", Yii::$app->params['appId'], $url);
        $url = str_replace("{__SECRET__}", Yii::$app->params['appSecret'], $url);
        
        Yii::info("---请求的url---".$url, 'wx');
        $res = Common::curlPost($url);
        
        Yii::info("---请求结果：".$res, 'wx');
        
        return $res;
    }
}