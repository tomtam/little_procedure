<?php
namespace campaign\modules\wap\controllers;

use Yii;
use campaign\components\Code;
use campaign\models\Share;

class ShareController extends BaseController{
    public $modelClass = '';
    
    private $__perNum = 10;
    public function beforeAction($action){
        parent::beforeAction($action);
        return  true;
    }
    /**
    * @date: 2017年3月1日 上午10:38:23
    * @author: louzhiqiang
    * @return:
    * @desc:   活动列表
    */
    public function actionList(){
        $page = Yii::$app->request->get("page", 1);
        
        $where = ['isDel' => Code::NOT_DEL_STATUS];
        
        $list = Share::find()
                    ->where($where)
                    ->orderBy(['isStick'=>SORT_DESC, 'createTime'=>SORT_DESC])
                    ->limit($this->__perNum)
                    ->offset(($page - 1) * $this->__perNum)
                    ->asArray()
                    ->all();
        
        foreach ($list as $k=>$shareItem){
            if(Share::SHARE_TYPE_IMG == $shareItem['shareType']){
                $imgArr = explode(Code::JS_STR_SEPARATOR, $shareItem['content']);
                $imgArrRes = array_map(function($v){
                    return "/upload/".$v;
                }, $imgArr);
                $list[$k]['content'] = $imgArrRes;
		$list[$k]['image_first'] = $imgArrRes[0];
		$list[$k]['image_sec'] = $imgArrRes[1];
		$list[$k]['image_third'] = $imgArrRes[2];
            }
            $list[$k]['createTime'] = date("Y-m-d H:i:s", $shareItem['createTime']);
        }
        
        $count = Share::find()
                    ->where($where)
                    ->count();
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => array(
                'list' => $list,
                'count' => $count
            ),
        ), JSON_UNESCAPED_UNICODE);
    }
    /**
    * @date: 2017年3月1日 下午4:28:09
    * @author: louzhiqiang
    * @return:
    * @desc:   详情
    */
    public function actionDetail(){
        $id = Yii::$app->request->get("id");
        
        $info = Share::find()
                        ->where(['id'=> $id, 'isDel' => Code::NOT_DEL_STATUS])
                        ->asArray()
                        ->one();
        
        if(Share::SHARE_TYPE_IMG == $info['shareType']){
            $imgArr = explode(Code::JS_STR_SEPARATOR, $info['content']);
            $imgArrRes = array_map(function($v){
                return "/upload/".$v;
            }, $imgArr);
            $info['content'] = $imgArrRes;
        }
        $info['createTime'] = date("Y-m-d H:i:s", $info['createTime']);
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $info,
        ), JSON_UNESCAPED_UNICODE);
        
    }
}
