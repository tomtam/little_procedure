<?php
namespace campaign\controllers;

use Yii;
use campaign\controllers\BaseController;
use campaign\models\Campaign;
use campaign\components\Code;
use campaign\components\UploadImg;
use campaign\models\Content;
use campaign\models\Search;
use campaign\models\Order;

class CampaignController extends BaseController{
    private $__camp_model;
    private $__perNum = 20;
    
    public $layout = "main";
    
    public function beforeAction($action){
        parent::beforeAction($action);
        $this->__camp_model = new Campaign();
        return true;
    }
    /**
    * @date: 2017年1月18日 下午4:35:11
    * @author: louzhiqiang
    * @return:
    * @desc:   列表页面
    */
    public function actionIndex(){
        $page = Yii::$app->request->get("page");
        if($page <= 0 ){
            $page = 1;
        }
        
        $where = array();
        $where = ['and'];
        $where[] = ['isDel' => Code::NOT_DEL_STATUS];
        $title = Yii::$app->request->get("title");
        if($title){
            $where[] = ['like', 'title', $title];
        }
        $origin = Yii::$app->request->get("origin");
        if($origin && $origin!="请选择"){
            $where[] = ['origin' => $origin];
        }
        if($beginTime = Yii::$app->request->get('beginTime')){
            $where[] = ['>' , 'beginTime', strtotime($beginTime)];
        }
        if($endTime = Yii::$app->request->get('endTime')){
            $where[] = ['<' , 'endTime', strtotime($endTime)];
        }
        $list = $this->__camp_model->getList($where, $page, $this->__perNum);
        foreach ($list as $key=>$camp){
            if(isset($camp['campType']) && $camp['campType']){
                $arrCampType = array_filter(explode(Code::STR_SEPARATOR, $camp['campType']));
                foreach ($arrCampType as $typeId){
                    $strTemp .= Campaign::$campTypeArr[$typeId] . ",";
                }
                $list[$key]['campType'] = trim($strTemp, ",");
            }
        }
        $count = $this->__camp_model->getCount($where);
        $totalPage = ceil($count/$this->__perNum);
        if($totalPage){
            $page_arr = range(max($page-2, 1), min($page+2, $totalPage));
        }else{
            $page_arr = array();
        }
        
        
        $origin_arr = $this->__camp_model->getOriginArr();
        
        $arr_render = array(
            'list' => $list,
            'count' => $count,
            'page'  => $page,
            'totalPage' => $totalPage,
            'perNum' => $this->__perNum,
            'pageArr' => $page_arr,
            'originArr' => $origin_arr,
            'beginTime' => $beginTime,
            'endTime' => $endTime,
            'originVal' => $origin,
            'title' => $title,
        );
        return $this->render("index", $arr_render);
    }
    /**
    * @date: 2017年1月18日 下午10:46:07
    * @author: louzhiqiang
    * @return:
    * @desc:    添加界面
    */
    public function actionAdd(){
        $arr_render = array(
            'campTypeArr' => Campaign::$campTypeArr,
        );
        return $this->render('add', $arr_render);
    }
    /**
    * @date: 2017年1月18日 下午4:35:53
    * @author: louzhiqiang
    * @return:
    * @desc:   添加
    */
    public function actionAddDo(){
        $title          = Yii::$app->request->post('title');
        $destination    = Yii::$app->request->post('destination');//目的地
        $rendezvous     = Yii::$app->request->post('rendezvous');//集合地
        $price          = Yii::$app->request->post('price');//单价
        $origin         = Yii::$app->request->post('origin');//来源
        $totalNum       = Yii::$app->request->post('totalNum');//活动人数
        $beginTime      = strtotime(Yii::$app->request->post('beginTime'));//开始时间
        $endTime        = strtotime(Yii::$app->request->post('endTime'));//结束时间
        $dayNum         = date("Ymd", $endTime) - date("Ymd", $beginTime);//活动天数 
        $campType       = Code::STR_SEPARATOR. join(Yii::$app->request->post('campType'), Code::STR_SEPARATOR) .Code::STR_SEPARATOR;//活动种类
        $locationName   = Yii::$app->request->post('locationName');//活动所在地
        
        //存储图片
        $_FILES['campImg']['name'] = array_filter($_FILES['campImg']['name']);
        $_FILES['campImg']['type'] = array_filter($_FILES['campImg']['type']);
        $_FILES['campImg']['tmp_name'] = array_filter($_FILES['campImg']['tmp_name']);
        $_FILES['campImg']['size'] = array_filter($_FILES['campImg']['size']);
        
        $uploadImg = new UploadImg();
        $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
        $path .= ".." . DIRECTORY_SEPARATOR;
        $path .= "web" .DIRECTORY_SEPARATOR. "upload";
        $uploadImg->set("path", $path);
        $uploadImg->upload('campImg');
        $imgArr = $uploadImg->getFileName();
        if(!$imgArr){
            Yii::info("------上传图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'camp');
            return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
        }
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
        $tx = Campaign::getDb()->beginTransaction();
        try{
            $model_camp = new Campaign();
            $model_camp->title       = $title;
            $model_camp->destination = $destination;
            $model_camp->rendezvous  = $rendezvous;
            $model_camp->price       = $price;
            $model_camp->origin      = $origin;
            $model_camp->totalNum    = $totalNum;
            $model_camp->beginTime   = $beginTime;
            $model_camp->endTime     = $endTime;
            $model_camp->dayNum      = $dayNum;
            $model_camp->campType    = $campType;
            $model_camp->locationName= $locationName;
            $model_camp->createTime  = time();
            $result_insert = $model_camp->save();
            $result_insert_id = $model_camp->id;
            
            foreach ($imgArr as $img){
                $model_content = new Content();
                $model_content->campId = $result_insert_id;
                $model_content->fieldName = Content::FIELD_IMAGE;
                $model_content->fieldTitle= "图片";
                $model_content->content = $img;
                $model_content->save();
            }
            //上传头像的url
            $model_content = new Content();
            $model_content->campId = $result_insert_id;
            $model_content->fieldName = Content::FIELD_HEAD_IMAGE;
            $model_content->fieldTitle= "头像";
            $model_content->content = $headImg;
            $model_content->save();
            
            if(Yii::$app->request->post('lineIntroduction')){
                $model_content = new Content();
                $model_content->campId = $result_insert_id;
                $model_content->fieldName = Content::FIELD_lINE_INTRODUCTION;
                $model_content->fieldTitle = Content::FIELD_LINE_INTRODUCTION_NAME;
                $model_content->content = Yii::$app->request->post('lineIntroduction');
                $model_content->save();
            }
            if(Yii::$app->request->post('scheduling')){
                $model_content = new Content();
                $model_content->campId = $result_insert_id;
                $model_content->fieldName = Content::FIELD_SCHEDULING;
                $model_content->fieldTitle = Content::FIELD_SCHEDULING_NAME;
                $model_content->content = Yii::$app->request->post('scheduling');
                $model_content->save();
            }
            if(Yii::$app->request->post('expenseExplanation')){
                $model_content = new Content();
                $model_content->campId = $result_insert_id;
                $model_content->fieldName = Content::FIELD_EXPENSE_EXPLANATION;
                $model_content->fieldTitle = Content::FIELD_EXPENSE_EXPLANATION_NAME;
                $model_content->content = Yii::$app->request->post('expenseExplanation');
                $model_content->save();
            }
            if(Yii::$app->request->post('moreIntroduction')){
                $model_content = new Content();
                $model_content->campId = $result_insert_id;
                $model_content->fieldName = Content::FIELD_MORE_INTRODUCTION;
                $model_content->fieldTitle = Content::FIELD_MORE_INTRODUCTION_NAME;
                $model_content->content = Yii::$app->request->post('moreIntroduction');
                $model_content->save();
            }
            if(Yii::$app->request->post('campType')){
                foreach (Yii::$app->request->post('campType') as $type){
                    $model_search = new Search();
                    $model_search->campId = $result_insert_id;
                    $model_search->fieldName = Search::FIELD_TYPE;
                    $model_search->content = $type;
                    $model_search->save();
                }
            }
            $tx->commit();
        }catch (\yii\db\IntegrityException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\UnknownPropertyException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\db\Exception $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\ErrorException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }
        
        if(!$result_insert || isset($message)){
            Yii::info("-----------添加活动报错:------".$message, 'camp');
            return Code::errorExit(Code::ERROR_CAMP_INSERT);
        }
        $this->redirect(['campaign/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年1月18日 下午4:36:00
    * @author: louzhiqiang
    * @return:
    * @desc:   删除
    */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        $model_camp = Campaign::findOne(['id' => $id]);
        $model_camp->isDel = Code::DEL_STATUS;
        $result = $model_camp->save();
        if($result){
            return Code::errorExit(Code::SUCC);
        }else{
            return Code::errorExit(Code::ERROR_CAMP_DELETE);
        }
    }
    /**
    * @date: 2017年1月18日 下午4:36:11
    * @author: louzhiqiang
    * @return:
    * @desc:   更新
    */
    public function actionUpdate(){
        $id = Yii::$app->request->get("id");
        
        $info = Campaign::find()->where(['id'=>$id])->asArray()->one();
        
        $info['imageArr'] = Content::find()
                                ->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_IMAGE])
                                ->asArray()
                                ->all();
        $info['headImg'] = Content::find()
                                ->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_HEAD_IMAGE])
                                ->asArray()
                                ->one();
        $res = Content::find()->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_EXPENSE_EXPLANATION])->asArray()->one();
        $info[Content::FIELD_EXPENSE_EXPLANATION] = $res['content'];
        $res = Content::find()->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_lINE_INTRODUCTION])->asArray()->one();
        $info[Content::FIELD_lINE_INTRODUCTION] = $res['content'];
        $res = Content::find()->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_MORE_INTRODUCTION])->asArray()->one();
        $info[Content::FIELD_MORE_INTRODUCTION] = $res['content'];
        $res = Content::find()->where(['campId'=>$info['id'], 'fieldName'=>Content::FIELD_SCHEDULING])->asArray()->one();
        $info[Content::FIELD_SCHEDULING] = $res['content'];
        
        $info['campType'] = array_filter(explode(Code::STR_SEPARATOR, $info['campType']));
        
        $arr_render = array(
            'info' => $info,
            'campTypeArr' => Campaign::$campTypeArr,
        );
        return $this->render("update", $arr_render);
    }
    /**
    * @date: 2017年1月21日 上午10:32:58
    * @author: louzhiqiang
    * @return:
    * @desc:   删除图片
    */
    public function actionImgDel(){
        $id = Yii::$app->request->post('id');
        
        $count = Content::deleteAll(['id'=>$id]);
        
        if($count){
            return Code::errorExit(Code::SUCC);
        }else{
            return Code::errorExit(Code::ERROR_IMAGE_DEL);
        }
    }
    /**
    * @date: 2017年1月18日 下午11:19:53
    * @author: louzhiqiang
    * @return:
    * @desc:   更新动作
    */
    public function actionUpdateDo(){
        $title          = Yii::$app->request->post('title');
        $destination    = Yii::$app->request->post('destination');//目的地
        $rendezvous     = Yii::$app->request->post('rendezvous');//集合地
        $price          = Yii::$app->request->post('price');//单价
        $origin         = Yii::$app->request->post('origin');//来源
        $totalNum       = Yii::$app->request->post('totalNum');//活动人数
        $beginTime      = strtotime(Yii::$app->request->post('beginTime'));//开始时间
        $endTime        = strtotime(Yii::$app->request->post('endTime'));//结束时间
        $dayNum         = date("Ymd", $endTime) - date("Ymd", $beginTime);//活动天数 
        $campType       = Code::STR_SEPARATOR. join(Yii::$app->request->post('campType'), Code::STR_SEPARATOR) .Code::STR_SEPARATOR;//活动种类
        $locationName   = Yii::$app->request->post('locationName');//活动所在地
        $id = Yii::$app->request->post('id');
        
        //存储图片
        $_FILES['campImg']['name'] = array_filter($_FILES['campImg']['name']);
        $_FILES['campImg']['type'] = array_filter($_FILES['campImg']['type']);
        $_FILES['campImg']['tmp_name'] = array_filter($_FILES['campImg']['tmp_name']);
        $_FILES['campImg']['size'] = array_filter($_FILES['campImg']['size']);
        if(count($_FILES['campImg']['name'])){
            $uploadImg = new UploadImg();
            $path = dirname(__FILE__). DIRECTORY_SEPARATOR;
            $path .= ".." . DIRECTORY_SEPARATOR;
            $path .= "web" .DIRECTORY_SEPARATOR. "upload";
            $uploadImg->set("path", $path);
            $uploadImg->upload('campImg');
            $imgArr = $uploadImg->getFileName();
            if(!$imgArr){
                Yii::info("------上传图片失败：-----".print_r($uploadImg->getErrorMsg(), true), 'camp');
                return Code::errorExit(Code::ERROR_IMAGE_UPLOAD);
            }
        }
        if(count($_FILES['headImg']['name']) && $_FILES['headImg']['name']){
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
        $tx = Campaign::getDb()->beginTransaction();
        try{
            $model_camp = Campaign::findOne(['id'=>$id]);
            $model_camp->title       = $title;
            $model_camp->destination = $destination;
            $model_camp->rendezvous  = $rendezvous;
            $model_camp->price       = $price;
            $model_camp->origin      = $origin;
            $model_camp->totalNum    = $totalNum;
            $model_camp->beginTime   = $beginTime;
            $model_camp->endTime     = $endTime;
            $model_camp->dayNum      = $dayNum;
            $model_camp->campType    = $campType;
            $model_camp->locationName= $locationName;
            $result_update = $model_camp->save();
            
            if(isset($imgArr) && count($imgArr)){
                foreach ($imgArr as $img){
                    $model_content = new Content();
                    $model_content->campId = $id;
                    $model_content->fieldName = Content::FIELD_IMAGE;
                    $model_content->fieldTitle= "图片";
                    $model_content->content = $img;
                    $model_content->save();
                }
            }
            if(isset($headImg) && $headImg){
                $model_content = Content::findOne(['campId'=>$id, 'fieldName'=>Content::FIELD_HEAD_IMAGE]);
                $model_content->content = $headImg;
                $model_content->save();
            }
            if(Yii::$app->request->post('lineIntroduction')){
                $model_content = Content::findOne(['campId'=>$id, 'fieldName' => Content::FIELD_lINE_INTRODUCTION]);
                $model_content->content = Yii::$app->request->post('lineIntroduction');
                $model_content->save();
            }
            if(Yii::$app->request->post('scheduling')){
                $model_content = Content::findOne(['campId'=>$id, 'fieldName' => Content::FIELD_SCHEDULING]);
                $model_content->content = Yii::$app->request->post('scheduling');
                $model_content->save();
            }
            if(Yii::$app->request->post('expenseExplanation')){
                $model_content = Content::findOne(['campId'=>$id, 'fieldName' => Content::FIELD_EXPENSE_EXPLANATION]);
                $model_content->content = Yii::$app->request->post('expenseExplanation');
                $model_content->save();
            }
            if(Yii::$app->request->post('moreIntroduction')){
                $model_content = Content::findOne(['campId'=>$id, 'fieldName' => Content::FIELD_MORE_INTRODUCTION]);
                $model_content->content = Yii::$app->request->post('moreIntroduction');
                $model_content->save();
            }
            if(Yii::$app->request->post('campType')){
                Search::deleteAll(['campId'=>$id, 'fieldName'=>Search::FIELD_TYPE]);
                foreach (Yii::$app->request->post('campType') as $type){
                    $model_search = new Search();
                    $model_search->campId = $id;
                    $model_search->fieldName = Search::FIELD_TYPE;
                    $model_search->content = $type;
                    $model_search->save();
                }
            }
            //更新订单里的title数据
            $order_model = new Order();
            $order_model->updateCampTitle($title, $id);
            $tx->commit();
        }catch (\yii\db\IntegrityException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\UnknownPropertyException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\db\Exception $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }catch (\yii\base\ErrorException $e){
            $tx->rollback();
            $message = $e->getMessage()."---".$e->getTraceAsString();
        }
        
        if(!$result_update || isset($message)){
            Yii::info("-----------更新活动报错:------".$message, 'camp');
            return Code::errorExit(Code::ERROR_CAMP_UPDATE);
        }
        $this->redirect(['campaign/index']);
        Yii::$app->response->send();
    }
    /**
    * @date: 2017年1月18日 下午4:36:47
    * @author: louzhiqiang
    * @return:
    * @desc:   置顶操作
    */
    public function actionStick(){
        $id = Yii::$app->request->post('id');
        
        $count = $this->__camp_model->getMaxStick();
        
        $model_camp = Campaign::findOne(['id' => $id]);
        
        $model_camp->isStick = $count + 1;
        
        $result = $model_camp->save();
        
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
        
        $model_camp = Campaign::findOne(['id' => $id]);
        
        $model_camp->isStick = 0;
        $result = $model_camp->save();
        
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