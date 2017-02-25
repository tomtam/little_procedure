<?php
namespace campaign\modules\api\controllers;

use Yii;
use campaign\models\Order;
use campaign\models\Campaign;
use campaign\components\Code;
use campaign\models\Content;
use campaign\models\Evaluate;
use campaign\models\User;

class OrderController extends BaseController{
    public $modelClass = '';
    private $__perNum = 10;
    public function beforeAction($action){
        parent::beforeAction($action);
        $this->getLoginStatus();
        return true;
    }
    /**
    * @date: 2017年1月21日 下午4:41:36
    * @author: louzhiqiang
    * @return:
    * @desc:   订单添加
    */
    public function actionAdd(){
        $campId = Yii::$app->request->post('campId');
        $userList = Yii::$app->request->post('userList');
        $num = Yii::$app->request->post('num');
        $userId = $this->userId;
        Yii::info("-----添加订单---参数：".print_r(Yii::$app->request->post(), true), 'api');
        $campInfo = Campaign::findOne(['id' => $campId]);
        if($campInfo['totalNum'] < $num){
            return Code::errorExit(Code::ERROR_ORDER_CAMPNUM);
        }
        $userInfo = User::findOne(['id'=>$userId]);
        
        $userList_arr = json_decode($userList, true);
        
        if( count($userList_arr) != $num ){
            return Code::errorExit(Code::ERROR_ORDER_NUM);
        }
        
        try{
            $model_order = new Order();
            $model_order->userId = $userId;
            $model_order->campId = $campId;
            $model_order->num = $num;
            $model_order->mark = $userList;
            $model_order->phone = $userList_arr[0]['phone'];
            $model_order->userName = $userInfo['name'];
            $model_order->amount = $campInfo['price'] * $num;
            $model_order->campTitle = $campInfo['title'];
            $model_order->createTime = time();
            $model_order->updateTime = time();
            $result_insert = $model_order->save();
        }catch (\yii\db\IntegrityException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\UnknownPropertyException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\db\Exception $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\ErrorException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }
        
        if( !$result_insert && isset($message)){
            Yii::info("-----------添加订单失败------".$message, 'order');
            return Code::errorExit(Code::ERROR_ORDER_CREATE);
        }
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年1月21日 下午4:41:23
    * @author: louzhiqiang
    * @return:
    * @desc:   订单列表
    */
    public function actionList(){
        $userId = $this->userId;
        $page   = Yii::$app->request->post('page', 1);
        
        $condition = ['userId' => $userId];
        
        $list = Order::find()
                        ->where($condition)
                        ->orderBy(['updateTime'=> SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page-1) * $this->__perNum)
                        ->asArray()
                        ->all();
        //处理数据
        foreach ($list as $key=>$order){
            $campInfo = Campaign::findOne(['id' => $order['campId']]);
            $headImg = Content::find()
                                ->where(['campId'=>$campInfo['id'], 'fieldName'=>Content::FIELD_HEAD_IMAGE])
                                ->asArray()
                                ->one();
            
            $list[$key]['campInfo'] = array(
                'headImg' => Content::getImagePath($headImg['content']),
            );
            $list[$key]['statusMark'] = Order::$arr_order_status[$list[$key]['status']];
            $list[$key]['mark'] = json_decode($order['mark'], true);
            
            if(Order::STATUS_ORDER_CAMP_OVER == $list[$key]['status']){
                 $list[$key]['evaluateMark'] = Order::$arr_order_evaluate[$order['evaluateStatus']];
            }
        }
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $list,
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    /**
    * @date: 2017年2月8日 下午4:35:48
    * @author: louzhiqiang
    * @return:
    * @desc:   评价
    */
    public function actionEvaluate(){
        $starLevel = Yii::$app->request->post('starLevel');
        $orderId   = Yii::$app->request->post('orderId');
        $mark = Yii::$app->request->post('mark');
        $userId = $this->userId;
        
        $eva_model = new Evaluate();
        $eva_model->orderId = $orderId;
        $eva_model->starLevel = $starLevel;
        $eva_model->content   = $mark;
        $eva_model->userId    = $userId;
        $orderInfo = Order::findOne(['id'=> $orderId]);
        $eva_model->campId = $orderInfo['campId'];
        
        $eva_model->save();
        
        return Code::errorExit(Code::SUCC);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}