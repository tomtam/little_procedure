<?php
namespace campaign\controllers;

use Yii;
use yii\web\Controller;
use campaign\components\Code;
use campaign\models\User;
use campaign\components\VerifyCode;
use campaign\models\Login;

class LoginController extends Controller{
    public $layout = false;
    public function beforeAction($action){
        return true;
    }
    public function actionIndex(){
        return $this->render("index");
    }
    /**
    * @date: 2017年1月18日 下午9:56:54
    * @author: louzhiqiang
    * @return:
    * @desc:   登陆
    */
    public function actionDo(){
        $userName = Yii::$app->request->post('userName');
        $password = Yii::$app->request->post('passWord');
        $vcode    = Yii::$app->request->post('vcode');
        
        if($vcode != Yii::$app->session[Login::VERIFY_CODE_SESSION_KEY]){
            return Code::errorExit(Code::ERROR_VERIFY_CHECK);
        }
        if($userName != User::USER_ADMIN_USERNAME || md5($password) != md5(User::USER_ADMIN_PASSWORD) ){
            return Code::errorExit(Code::ERROR_USER_INFO); 
        }
        Yii::$app->session[User::USER_LOGIN_STATUS_KEY] = true;
        Yii::$app->session[Login::USERNAME_SESSION] = User::USER_ADMIN_USERNAME;
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年1月22日 下午3:31:47
    * @author: louzhiqiang
    * @return:
    * @desc:   登出
    */
    public function actionLogout(){
        Yii::$app->session[User::USER_LOGIN_STATUS_KEY] = false;
        Yii::$app->session[Login::USERNAME_SESSION] = '';
        $this->redirect(['/login']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年1月22日 下午3:08:37
    * @author: louzhiqiang
    * @return:
    * @desc:   获得验证码
    */
    public function actionVerifyCode(){
        $code = new VerifyCode();
        $code->doimg();
        Yii::$app->session[Login::VERIFY_CODE_SESSION_KEY] = $code->getCode();
    }
    public function afterAction($action, $result){
        exit($result);
    }
}