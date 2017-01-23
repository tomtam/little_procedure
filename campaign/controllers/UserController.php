<?php
namespace campaign\controllers;

use Yii;
use campaign\controllers\BaseController;
use campaign\models\User;

class UserController extends BaseController{
    private $__user_model;
    private $__perNum = 20;
    public  function beforeAction($action){
        parent::beforeAction($action);
        
        $this->__user_model = new User();
        return true;
    }
    /**
    * @date: 2017年1月18日 下午4:45:07
    * @author: louzhiqiang
    * @return:
    * @desc:   用户注册表
    */
    public function actionIndex(){
        $page = Yii::$app->request->get('page');
        $userName = Yii::$app->request->get('userName');
        
        $condition = array();
        if($userName){
            $condition = ['name' => $userName];
        }
        $list = User::find()
                        ->where($condition)
                        ->orderBy(['createTime'=>SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page - 1) * $this->__perNum)
                        ->asArray()
                        ->all();
        
        $count = User::find()
                        ->where($condition)
                        ->count();
        
        $totalPage = ceil($count/$this->__perNum);                    
        if($totalPage){
            $page_arr = range(max($page-2, 1), min($page+2, $totalPage));
        }else{
            $page_arr = array();
        }
        
        $arr_render = array(
            'list' => $list,
            'count' => $count,
            'totalPage' => $totalPage,
            'userName' => $userName,
            'pageArr'  => $page_arr,
            'perNum'   => $this->__perNum,
            'page'     => $page,
        );
        
        return $this->render('index', $arr_render);
    }
}