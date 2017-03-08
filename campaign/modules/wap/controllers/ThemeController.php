<?php
namespace campaign\modules\wap\controllers;

use Yii;
use campaign\models\Theme;
use campaign\components\Code;
use campaign\models\Campaign;
use campaign\models\Content;

class ThemeController extends BaseController{
    public $modelClass = '';
    
    private $__perNum = 10;
    public function beforeAction($action){
        parent::beforeAction($action);
        return true;
    }
    /**
    * @date: 2017年3月1日 上午10:36:12
    * @author: louzhiqiang
    * @return:
    * @desc:   列表
    */
    public function actionList(){
        $page = Yii::$app->request->get("page", 1);
        
        $where = ['isDel' => Code::NOT_DEL_STATUS];
        
        $list = Theme::find()
                        ->where($where)
                        ->orderBy(['isStick'=>SORT_DESC, 'createTime'=>SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page - 1) * $this->__perNum)
                        ->asArray()
                        ->all();
        foreach ($list as $k=>$item){
            $list[$k]['picUrl'] = "/upload/".$item['picUrl'];
            $list[$k]['createTime'] = date("Y-m-d H:i:s", $item['createTime']);
        }
        
        $count = Theme::find()
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
    * @date: 2017年3月1日 上午10:36:39
    * @author: louzhiqiang
    * @return:
    * @desc:  主题下的活动列表
    */
    public function actionCamp(){
        $id = Yii::$app->request->get("id");
        
        $info = Theme::find()->where(['id' => $id])->asArray()->one();
        
        $info['createTime'] = date("Y-m-d H:i:s", $info['createTime']);
        $info['updateTime'] = date("Y-m-d H:i:s", $info['updateTime']);
        $info['picUrl'] = "/upload/".$info['picUrl'];
        
        $campArr = Campaign::find()->where(['id'=>explode(Code::JS_STR_SEPARATOR, $info['campList'])])->asArray()->all();
        
        foreach ($campArr as $k=>$campaign){
            $headImg = Content::find()
                                ->where(['campId'=>$campaign['id'], 'fieldName' => Content::FIELD_HEAD_IMAGE])
                                ->asArray()
                                ->one();
            $campArr[$k]['headImg'] = Content::getImagePath($headImg['content']);
            $campArr[$k]['beginTime'] = date("Y-m-d", $campaign['beginTime']);
            $campArr[$k]['endTime'] = date("Y-m-d", $campaign['endTime']);
        }
        
        $info['campArr'] = $campArr;
        
        return json_encode(array(
            'code' => Code::SUCC,
            'info' => Code::$arr_code_status[Code::SUCC],
            'data' => $info
        ), JSON_UNESCAPED_UNICODE);
    }
}