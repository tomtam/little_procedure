<?php
namespace campaign\modules\api\controllers;

use Yii;
use yii\web\Controller;
use campaign\models\Order;
use campaign\models\Campaign;
use campaign\components\Code;
use campaign\models\Content;

class OrderController extends Controller{
    private $__perNum = 10;
    public function beforeAction($action){
        //通过验证密串儿，确认是否登陆
        return true;
    }
    /**
    * @date: 2017年1月21日 下午4:41:36
    * @author: louzhiqiang
    * @return:
    * @desc:   订单添加
    */
    public function actionAdd(){
        $userName = Yii::$app->request->post('userName');
        $campId = Yii::$app->request->post('campId');
        $num = Yii::$app->request->post('num');
        $mark = Yii::$app->request->post('mark');
        $phone = Yii::$app->request->post('phone');
        $userId = Yii::$app->request->post('userId');
        
        try{
            $model_order = new Order();
            $model_order->userId = $userId;
            $model_order->campId = $campId;
            $model_order->num = $num;
            $model_order->mark = $mark;
            $model_order->phone = $phone;
            $model_order->userName = $userName;
            $campInfo = Campaign::findOne(['id' => $campId]);
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
        $userId = Yii::$app->request->post('userId');
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
                'title' => $campInfo['title'],
                'headImg' => $headImg['content'],
            );
            $list[$key]['status'] = $orderStatus = Order::processStatus($campInfo);
            $list[$key]['statusMark'] = Order::$arr_order_status[$orderStatus];
            
            if(Order::STATUS_ORDER_CAMP_OVER == $orderStatus){
                    $list[$key]['evaluateMark'] = Order::$arr_order_evaluate[$order['evaluateStatus']];
                    //为true代表是可以评价的   false代表就是不可以去评价
                    $list[$key]['evaluate'] = ($order['evaluateStatus'] ? true : false);
            }
        }
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $list,
        ), JSON_UNESCAPED_UNICODE);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}