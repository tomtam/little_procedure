<?php
namespace campaign\modules\api\controllers;

use Yii;
use yii\web\Controller;
use campaign\models\User;
use campaign\components\Code;

class UserController extends Controller{
    public function beforeAction($action){
        return true;
    }
    /**
    * @date: 2017年1月22日 下午5:09:09
    * @author: louzhiqiang
    * @return:
    * @desc:   注册添加
    */
    public function actionRegister(){
        $userId   = Yii::$app->request->post('userId'); 
        $userName = Yii::$app->request->post('name');
        $photoUrl = Yii::$app->request->post('photoUrl');
        
        $model_user = new User();
        $model_user->id   = $userId;
        $model_user->name = $userName;
        $model_user->createTime = time();
        $model_user->photoUrl   = $photoUrl;
        $model_user->save();
        
        return Code::errorExit(Code::SUCC);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}