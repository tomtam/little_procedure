<?php
namespace campaign\controllers;

use Yii;
use yii\web\Controller;
use campaign\models\User;

class BaseController extends Controller{
    protected $username;
    public function beforeAction($action){
        //判断是否登陆
        if( !Yii::$app->session[User::USER_LOGIN_STATUS_KEY] ){
            $this->redirect("/login");
            Yii::$app->response->send();
        }
        $this->username = User::USER_ADMIN_USERNAME;
        return true;
    }
}