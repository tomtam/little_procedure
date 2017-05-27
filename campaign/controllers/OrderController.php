<?php
namespace campaign\controllers;

use Yii;
use campaign\controllers\BaseController;
use campaign\models\Order;
use campaign\models\Evaluate;
use campaign\models\Campaign;

class OrderController extends BaseController{
    private $__perNum = 20;
    public function beforeAction($action){
        parent::beforeAction($action);
        
        return true;
    }
    /**
    * @date: 2017年1月18日 下午4:43:18
    * @author: louzhiqiang
    * @return:
    * @desc:   订单列表
    */
    public function actionIndex(){
        $userName = Yii::$app->request->get('userName');
        $page     = Yii::$app->request->get("page", 1);
        $title    = Yii::$app->request->get('title');
        
        $condition = ['and'];
        if($userName) {
            $condition[] = ['userName' => $userName];
        }
        if($title){
            $condition[] = ['like', 'title', $title];
        }
        
        if($beginTime = Yii::$app->request->get('beginTime')){
            $where[] = ['>' , 'createTime', strtotime($beginTime)];
        }
        if($endTime = Yii::$app->request->get('endTime')){
            $where[] = ['<' , 'createTime', strtotime($endTime)];
        }
        
        $list = Order::find()
                        ->where($condition)
			->orderBy(['createTime' => SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page-1) * $this->__perNum)
                        ->asArray()
                        ->all();
        foreach ($list as $key=>$order){
            $campInfo = Campaign::findOne(['id' => $order['campId']]);
            $list[$key]['origin'] = $campInfo['origin'];
            $list[$key]['price'] = $campInfo['price'];
            $list[$key]['evaluateCount'] = Evaluate::find()->where(['orderId'=>$order['id']])->count();
            $list[$key]['status'] = Order::$arr_order_status[Order::processStatus($campInfo)];
	    $order_user_info = json_decode($order['mark'], true);
	    $orderUserInfo = "";
	    foreach($order_user_info as $user_info){
		$orderUserInfo .= "用户名：".$user_info['userName']."  电话：".$user_info['phone']." | ";
	    }
	    $list[$key]['orderUserInfo'] = trim($orderUserInfo, " | ");
        }
        
        $count = Order::find()
                        ->where($condition)
                        ->count();
        
        $totalPage = ceil($count / $this->__perNum);
        if($totalPage){
            $page_arr = range(max($page-2, 1), min($page+2, $totalPage));
        }else{
            $page_arr = array();
        }
        
        $arr_render = array(
            'list' => $list,
            'count' => $count,
            'totalPage' => $totalPage,
            'pageArr'   => $page_arr,
            'userName'  => $userName,
            'title'     => $title,
            'perNum'    => $this->__perNum,
            'beginTime' => $beginTime,
            'endTime'   => $endTime,
            'page'      => $page,
        );
        
        return $this->render("index", $arr_render);
    }
}
