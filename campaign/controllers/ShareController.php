<?php
namespace campaign\controllers;

use Yii;
use campaign\models\Share;
use campaign\components\Code;
use campaign\components\UploadImg;

class ShareController extends BaseController{
    
    private $__perNum = 10;
    private $__share_model;
    public function beforeAction($action){
        parent::beforeAction($action);
        
        $this->__share_model =  new Share();
        return true;
    }
    /**
    * @date: 2017年2月27日 下午9:58:19
    * @author: louzhiqiang
    * @return:
    * @desc:   分享列表页面
    */
    public function actionIndex(){
        
        $page = Yii::$app->request->get("page", 1);
        $title = trim(Yii::$app->request->get("title"));
        
        $where = ['and'];
        $where[] = ['isDel'=>Code::NOT_DEL_STATUS];
        if($title){
            $where[] = ['like', 'title', $title];
        }
        
        $list = Share::find()
                        ->where($where)
                        ->orderBy(['isStick'=>SORT_DESC, 'createTime'=>SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page - 1) * $this->__perNum)
                        ->asArray()
                        ->all();
        
        foreach ($list as $k=>$share){
            $list[$k]['shareTypeName'] = Share::$arrShareType[$share['shareType']];
            if($share['shareType'] == Share::SHARE_TYPE_IMG){
                $list[$k]['content'] = explode(Code::JS_STR_SEPARATOR, $share['content']);
            }
        }
        
        $count = Share::find()
                        ->where($where)
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
            'pageArr' => $page_arr,
            'page' => $page,
            'pageNum' => $this->__perNum,
            'title' => $title,
        );
        
        return $this->render("index", $arr_render);
    }
    /**
    * @date: 2017年2月27日 下午9:58:45
    * @author: louzhiqiang
    * @return:
    * @desc:   添加
    */
    public function actionAdd(){
        $arr_render = array(
            'typeArr' => Share::$arrShareType,
        );
        return $this->render("add", $arr_render);
    }
    /**
    * @date: 2017年2月27日 下午9:59:10
    * @author: louzhiqiang
    * @return:
    * @desc:   操作添加
    */
    public function actionAddDo(){
        $title = trim(Yii::$app->request->post('title'));
        $shareType = Yii::$app->request->post('shareType');
        $detail = trim(Yii::$app->request->post('detail'));
        
        if(Share::SHARE_TYPE_IMG == $shareType){
            //存储图片
            $_FILES['shareImg']['name'] = array_filter($_FILES['shareImg']['name']);
            $_FILES['shareImg']['type'] = array_filter($_FILES['shareImg']['type']);
            $_FILES['shareImg']['tmp_name'] = array_filter($_FILES['shareImg']['tmp_name']);
            $_FILES['shareImg']['size'] = array_filter($_FILES['shareImg']['size']);
            
            $uploadImg = new UploadImg();
            $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
            $path .= ".." . DIRECTORY_SEPARATOR;
            $path .= "web" .DIRECTORY_SEPARATOR. "upload";
            $uploadImg->set("path", $path);
            $uploadImg->upload('shareImg');
            $imgArr = $uploadImg->getFileName();
            if(!$imgArr){
                Yii::info("------上传图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'share');
                return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
            }
            $content = join($imgArr, Code::JS_STR_SEPARATOR);
        }else if(Share::SHARE_TYPE_VIDEO == $shareType){
            $content = Yii::$app->request->post('shareVideo');
        }else{
            Yii::info("-----缺少参数shareType", 'share');
            return Code::errorExit(Code::ERROR_PARAM_CHECK);
        }
        
        $model_share = new Share();
        $model_share->title = $title;
        $model_share->content = $content;
        $model_share->createTime = time();
        $model_share->updateTime = time();
        $model_share->shareType = $shareType;
        $model_share->detail = $detail;
        $model_share->save();
        
        $this->redirect(['share/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年2月27日 下午9:59:31
    * @author: louzhiqiang
    * @return:
    * @desc:   更新
    */
    public function actionUpdate(){
        $id = Yii::$app->request->get('id');
        
        $info = Share::find()
                        ->where(['id' => $id])
                        ->asArray()
                        ->one();
        $info['content'] = $info['shareType'] == Share::SHARE_TYPE_VIDEO ? $info['content'] : explode(Code::JS_STR_SEPARATOR, $info['content']);
                        
        $arr_render = array(
            'info' => $info,
            'typeArr' => Share::$arrShareType,
        );
        
        return $this->render('update', $arr_render);
    }
    /**
    * @date: 2017年2月27日 下午9:59:45
    * @author: louzhiqiang
    * @return:
    * @desc:   更新
    */
    public function actionUpdateDo(){
        $title = trim(Yii::$app->request->post('title'));
        $shareType = Yii::$app->request->post('shareType');
        $detail = trim(Yii::$app->request->post('detail'));
        $id = Yii::$app->request->post('id');
        
        if(Share::SHARE_TYPE_IMG == $shareType && count(array_filter($_FILES['shareImg']['name']))){
            //存储图片
            $_FILES['shareImg']['name'] = array_filter($_FILES['shareImg']['name']);
            $_FILES['shareImg']['type'] = array_filter($_FILES['shareImg']['type']);
            $_FILES['shareImg']['tmp_name'] = array_filter($_FILES['shareImg']['tmp_name']);
            $_FILES['shareImg']['size'] = array_filter($_FILES['shareImg']['size']);
        
            $uploadImg = new UploadImg();
            $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
            $path .= ".." . DIRECTORY_SEPARATOR;
            $path .= "web" .DIRECTORY_SEPARATOR. "upload";
            $uploadImg->set("path", $path);
            $uploadImg->upload('shareImg');
            $imgArr = $uploadImg->getFileName();
            if(!$imgArr){
                Yii::info("------上传图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'share');
                return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
            }
            $content = join($imgArr, Code::JS_STR_SEPARATOR);
        }else if(Share::SHARE_TYPE_VIDEO == $shareType){
            $content = Yii::$app->request->post('shareVideo');
        }
        
        $model_share = Share::findOne($id);
        $model_share->title = $title;
        if(isset($content)){
            if(Share::SHARE_TYPE_IMG == $shareType){
                $model_share->content = $model_share['content'].Code::JS_STR_SEPARATOR.$content;
            }elseif(Share::SHARE_TYPE_VIDEO == $shareType){
                $model_share->content = $content;
            }
        }
        $model_share->updateTime = time();
        $model_share->shareType = $shareType;
        $model_share->detail = $detail;
        $model_share->save();
        
        $this->redirect(['share/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年2月28日 下午10:46:33
    * @author: louzhiqiang
    * @return:
    * @desc:   删除图片
    */
    public function actionDelImg(){
        $id = Yii::$app->request->post('id');
        $img = Yii::$app->request->post('img');
        
        $info = Share::findOne($id);
        $imgArr = explode(Code::JS_STR_SEPARATOR, $info['content']);
        $key = array_search($img, $imgArr);
        unset($imgArr[$key]);
        $info->content = join($imgArr, Code::JS_STR_SEPARATOR);
        $info->updateTime = time();
        $info->save();
        
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年2月27日 下午10:00:10
    * @author: louzhiqiang
    * @return:
    * @desc:   删除
    */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        
        $model_share = Share::findOne(['id' => $id]);
        $model_share->isDel = Code::DEL_STATUS;
        $model_share->updateTime = time();
        $model_share->save();
        
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年2月27日 下午10:00:31
    * @author: louzhiqiang
    * @return:
    * @desc:   置顶操作
    */
    public function actionStick(){
        $id = Yii::$app->request->post('id');
        
        $count = $this->__share_model->getMaxStick();
        
        $model_theme = Share::findOne(['id' => $id]);
        
        $model_theme->isStick = $count + 1;
        $model_theme->updateTime = time();
        
        $result = $model_theme->save();
        
        if($result){
            return Code::errorExit(Code::SUCC);
        }else{
            return Code::errorExit(Code::ERROR_CAMP_STICK);
        }
    }
    /**
    * @date: 2017年2月27日 下午10:01:02
    * @author: louzhiqiang
    * @return:
    * @desc:   取消置顶
    */
    public function actionCancelStick(){
        $id = Yii::$app->request->post('id');
        
        $model_theme = Share::findOne(['id' => $id]);
        
        $model_theme->isStick = 0;
        $model_theme->updateTime = time();
        $result = $model_theme->save();
        
        if($result){
            return Code::errorExit(Code::SUCC);
        }else{
            return Code::errorExit(Code::ERROR_CAMP_CANCEL_STICK);
        }
    }
    public function afterAction($action, $result){
        exit($result);
    }
}