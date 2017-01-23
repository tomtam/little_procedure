<?php
namespace campaign\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller{
    public function beforeAction($action){
        return true;
    }
    
    public function actionIndex(){
        $this->redirect(['campaign/index']);
        Yii::$app->response->send();
    }
}