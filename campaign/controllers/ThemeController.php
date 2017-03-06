<?php
namespace campaign\controllers;

use Yii;
use campaign\models\Theme;
use campaign\components\Code;
use campaign\components\UploadImg;
use campaign\models\Campaign;

class ThemeController extends BaseController{
    
    private $__theme_model;
    private $__perNum = 10;
    
    public function beforeAction($action){
        parent::beforeAction($action);
        $this->__theme_model = new Theme();        
        return true;
    }
    /**
    * @date: 2017年2月23日 下午5:22:57
    * @author: louzhiqiang
    * @return:
    * @desc:   列表
    */
    public function actionIndex(){
        $page = Yii::$app->request->get('page', 1);
        $title = Yii::$app->request->get('title');
        
        $where = ['and'];
        $where[] = ['isDel'=> Code::NOT_DEL_STATUS]; 
        
        if($title){
            $where[] = ['like', 'title', $title];
        }
        
        $list = Theme::find()
                        ->where($where)
                        ->orderBy(['isStick'=>SORT_DESC, 'createTime'=>SORT_DESC])
                        ->limit($this->__perNum)
                        ->offset(($page-1) * $this->__perNum)
                        ->asArray()
                        ->all();
        
         foreach ($list as $k=>$theme){
             $list[$k]['campList'] = Campaign::find()
                                                ->select(['id', 'title'])
                                                ->where(['id'=>array_filter(explode(Code::JS_STR_SEPARATOR, $theme['campList']))])
                                                ->asArray()
                                                ->all();
         }
        
         $count = Theme::find()
                        ->where($where)
                        ->count();
         
         $totalPage = ceil( $count / $this->__perNum);
         if($totalPage){
             $page_arr = range(max($page-2, 1), min($page+2, $totalPage));
         }else{
             $page_arr = array();
         }
         
         $arr_render = array(
             'count' => $count,
             'list'  => $list,
             'totalPage' => $totalPage,
             'title' => $title,
             'page' => $page,
             'perNum' => $this->__perNum,
             'pageArr' => $page_arr,
         );
         
         return $this->render('index', $arr_render);
    }
    /**
    * @date: 2017年2月23日 下午6:45:19
    * @author: louzhiqiang
    * @return:
    * @desc:   置顶
    */
    public function actionStick(){
        $id = Yii::$app->request->post('id');
        
        $count = $this->__theme_model->getMaxStick();
        
        $model_theme = Theme::findOne(['id' => $id]);
        
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
     * @date: 2017年1月22日 上午11:36:27
     * @author: louzhiqiang
     * @return:
     * @desc:   取消置顶
     */
    public function actionCancelStick(){
        $id = Yii::$app->request->post('id');
    
        $model_theme = Theme::findOne(['id' => $id]);
    
        $model_theme->isStick = 0;
        $model_theme->updateTime = time();
        $result = $model_theme->save();
    
        if($result){
            return Code::errorExit(Code::SUCC);
        }else{
            return Code::errorExit(Code::ERROR_CAMP_CANCEL_STICK);
        }
    }
    /**
    * @date: 2017年2月23日 下午5:23:21
    * @author: louzhiqiang
    * @return:
    * @desc:   添加主题
    */
    public function actionAdd(){
        return $this->render('add');
    }
    /**
    * @date: 2017年2月23日 下午5:25:58
    * @author: louzhiqiang
    * @return:
    * @desc:   添加动作
    */
    public function actionAddDo(){
        $title = trim(Yii::$app->request->post('title'));
        $introduction = trim(Yii::$app->request->post('themeIntroduction'));
        
        //上传图片
        $uploadImg = new UploadImg();
        $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
        $path .= ".." . DIRECTORY_SEPARATOR;
        $path .= "web" .DIRECTORY_SEPARATOR. "upload";
        $uploadImg->set("path", $path);
        $uploadImg->upload('headImg');
        $headImg = $uploadImg->getFileName();
        if(!$headImg){
            Yii::info("------上传头像图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'camp');
            return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
        }
        
        try{
            $model_theme = new Theme();
            $model_theme->createTime = time();
            $model_theme->updateTime = time();
            $model_theme->picUrl = $headImg;
            $model_theme->introduction = $introduction;
            $model_theme->title = $title;
            $model_theme->isDel = Code::NOT_DEL_STATUS;
            $res_insert = $model_theme->save();
        }catch (\yii\db\IntegrityException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\UnknownPropertyException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\db\Exception $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\ErrorException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }
        
        if(!$res_insert || isset($message)){
            Yii::info("----插入主题报错：".$message, 'theme');
            return Code::errorExit(Code::ERROR_CAMP_INSERT);
        }
        
        $this->redirect(['theme/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年2月23日 下午5:24:07
    * @author: louzhiqiang
    * @return:
    * @desc:  删除
    */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        
        $model_theme = Theme::findOne(['id'=> $id]);
        $model_theme->isDel = Code::DEL_STATUS;
        $model_theme->updateTime - time();
        $model_theme->save();
        
        return Code::errorExit(Code::SUCC);
    }
    /**
    * @date: 2017年2月23日 下午5:24:29
    * @author: louzhiqiang
    * @return:
    * @desc:    修改
    */
    public function actionUpdate(){
        $id = Yii::$app->request->get('id');
        
        $info = Theme::find()
                        ->where(['id' => $id])
                        ->asArray()
                        ->one();
        
        $arr_render = array(
            'info' => $info
        ); 
        
        return $this->render('update', $arr_render); 
    }
    /**
    * @date: 2017年2月23日 下午5:25:39
    * @author: louzhiqiang
    * @return:
    * @desc:   修改动作
    */
    public function actionUpdateDo(){
        $title = trim(Yii::$app->request->post('title'));
        $introduction = trim(Yii::$app->request->post('themeIntroduction'));
        $id = Yii::$app->request->post('id');
        
        if($_FILES['headImg']['name']){
            //上传图片
            $uploadImg = new UploadImg();
            $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
            $path .= ".." . DIRECTORY_SEPARATOR;
            $path .= "web" .DIRECTORY_SEPARATOR. "upload";
            $uploadImg->set("path", $path);
            $uploadImg->upload('headImg');
            $headImg = $uploadImg->getFileName();
            if(!$headImg){
                Yii::info("------上传头像图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'camp');
                return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
            }
        }
        
        try{
            $model_theme = Theme::findOne(['id' => $id]);
            $model_theme->updateTime = time();
            if(isset($headImg) && $headImg){
                $model_theme->picUrl = $headImg;
            }
            $model_theme->introduction = $introduction;
            $model_theme->title = $title;
            $res_insert = $model_theme->save();
        }catch (\yii\db\IntegrityException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\UnknownPropertyException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\db\Exception $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\ErrorException $e){
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }
        
        if(!$res_insert || isset($message)){
            Yii::info("----更新主题报错：".$message, 'theme');
            return Code::errorExit(Code::ERROR_CAMP_INSERT);
        }
        
        $this->redirect(['theme/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年2月27日 下午6:15:39
    * @author: louzhiqiang
    * @return:
    * @desc:   删除制定活动的id
    */
    public function actionCampDel(){
        $themeId = Yii::$app->request->post('themeId');
        $campId  = Yii::$app->request->post('campId');
        
        $model_theme = Theme::findOne(['id' => $themeId]);
        $campListArr = explode(Code::JS_STR_SEPARATOR, $model_theme->campList);
        $searchKey = array_search($campId, $campListArr);
        if($searchKey === false){
            Yii::info("--------删除活动时没有发现活动id----".$campId."------".print_r($campListArr, true), 'theme');
            return Code::errorExit(Code::SUCC);
        }
        
        unset($campListArr[$searchKey]);
        $model_theme->campList = join($campListArr, Code::JS_STR_SEPARATOR);
        $model_theme->updateTime = time();
        $model_theme->save();
        
        return Code::errorExit(Code::SUCC);
    }
    public function afterAction($action, $result){
        exit($result);
    }
}