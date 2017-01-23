<?php
namespace campaign\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use campaign\components\Code;
use campaign\models\Campaign;
use campaign\models\Search;
use campaign\models\Content;
use campaign\components\XUtils;
use campaign\models\Evaluate;

class CampaignController extends ActiveController{
    public $modelClass = '';
    
    private $__perNum = 10;
    public function beforeAction($action){
        return true;
    }
    public function actionList(){
        $locationName = Yii::$app->request->post('locationName');
        $campType     = Yii::$app->request->post('campType');
        $keyword = Yii::$app->request->post('keyword');
        $page         = Yii::$app->request->post('page', 1);
        
        //查找指定数据
        if($campType){
            $campId = array();
            $arrCampType = array_filter(explode(Code::STR_SEPARATOR, $campType));
            $arrCampId = Search::find()->where(['content'=>$arrCampType, 'fieldName'=>Search::FIELD_TYPE])->asArray()->all();
            foreach ($arrCampId as $search){
                $campId[] = $search['campId'];
            }
            $campId = array_filter($campId);
        }
        
        $where = ['and'];
        $where[] = ['isDel' => Code::NOT_DEL_STATUS];
        if(isset($campId) && $campId){
            $where[] = ['campId' => $campId];
        }
        if($locationName){
            $where[] = ['locationName' => array_filter(explode(Code::STR_SEPARATOR, $locationName))];
        }
        if($keyword){
            $where[] = ['like', 'title', $keyword];
        }
        
        $list = Campaign::find()
                        ->where($where)
                        ->orderBy(['isStick'=>SORT_DESC, 'updateTime'=>SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page - 1) * $this->__perNum)
                        ->asArray()
                        ->all();
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $list,
        ), JSON_UNESCAPED_UNICODE);
    }   
    /**
    * @date: 2017年1月21日 下午4:51:59
    * @author: louzhiqiang
    * @return:
    * @desc:   活动主题的列表
    */
    public function actionTypeList(){
        return json_encode(array(
            'code' => Code::SUcc,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => Campaign::$campTypeArr,
        ), JSON_UNESCAPED_UNICODE);
    }
    /**
    * @date: 2017年1月21日 下午5:25:31
    * @author: louzhiqiang
    * @return:
    * @desc:   获得详情
    */
    public function actionDetail(){
        $campId = Yii::$app->request->post('id');
        $page   = Yii::$app->request->post('page', 1);
        
        $info = Campaign::find()->where(['id'=>$campId])->asArray()->one();
        
        $info['imageArr'] = Content::find()
                                    ->where(['campId'=>$campId, 'fieldName'=>Content::FIELD_IMAGE])
                                    ->asArray()
                                    ->all();
        $info[Content::FIELD_lINE_INTRODUCTION] = Content::find()
                                    ->where(['campId'=>$campId, 'fieldName'=>Content::FIELD_lINE_INTRODUCTION])
                                    ->asArray()
                                    ->one();
       $info[Content::FIELD_EXPENSE_EXPLANATION] = Content::find()
                                    ->where(['campId'=>$campId, 'fieldName'=>Content::FIELD_EXPENSE_EXPLANATION])
                                    ->asArray()
                                    ->one();
       $info[Content::FIELD_MORE_INTRODUCTION] = Content::find()
                                    ->where(['campId'=>$campId, 'fieldName'=>Content::FIELD_MORE_INTRODUCTION])
                                    ->asArray()
                                    ->one();
       $info[Content::FIELD_SCHEDULING] = Content::find()
                                    ->where(['campId'=>$campId, 'fieldName'=>Content::FIELD_SCHEDULING])
                                    ->asArray()
                                    ->one();
       
       //评价数据
       $evaluate_arr = Evaluate::find()
                                ->where(['campId'=>$info['id']])
                                ->orderBy(['createTime' => SORT_DESC])
                                ->limit($this->__perNum)
                                ->offset(($page - 1) * $this->__perNum)
                                ->asArray()
                                ->all();
       
       $info['evaluateArr'] = $evaluate_arr;
       
       return json_encode(array(
           'code' => Code::SUCC,
           'info' => Code::$arr_code_status[Code::SUCC],
           'data' => $info,
       ), JSON_UNESCAPED_UNICODE);
    }
    /**
    * @date: 2017年1月21日 下午5:29:50
    * @author: louzhiqiang
    * @return:
    * @desc:   获得整个的活动地区列表
    */
    public function actionLocationName(){
        $model_campaign = new Campaign();
        
        $arr = $model_campaign->getLocationName();
        $arr = XUtils::my_sort($arr, 'count', SORT_DESC);
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $arr
        ), JSON_UNESCAPED_UNICODE);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}