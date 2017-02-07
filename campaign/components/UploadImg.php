<?php
namespace campaign\components;
/**
 * @author Chiwm() Eiwen
 * Image 图片处理类
 * @package Util
 */
class UploadImg {
	private $path = "./uploads";          //上传文件保存的路径
	private $allowtype = array('jpg','jpeg','gif','png'); //设置限制上传文件的类型
	private $maxsize = 1000000;           //限制文件上传大小（字节）
	private $israndname = true;           //设置是否随机重命名文件， false不随机
	
	private $originName;              //源文件名
	private $tmpFileName;              //临时文件名
	private $fileType;               //文件类型(文件后缀)
	private $fileSize;               //文件大小
	private $newFileName;              //新文件名
	private $errorNum = 0;             //错误号
	private $errorMess="";             //错误报告消息
	
	/**
	 * 用于设置成员属性（$path, $allowtype,$maxsize, $israndname）
	 * 可以通过连贯操作一次设置多个属性值
	 *@param  string $key  成员属性名(不区分大小写)
	 *@param  mixed  $val  为成员属性设置的值
	 *@return  object     返回自己对象$this，可以用于连贯操作
	 */
	function set($key, $val){
		$key = strtolower($key);
		if( array_key_exists( $key, get_class_vars(get_class($this) ) ) ){
			$this->setOption($key, $val);
		}
		return $this;
	}
	
	/**
	 * 调用该方法上传文件
	 * @param  string $fileFile  上传文件的表单名称
	 * @return bool        如果上传成功返回数true
	 */
	
	function upload($fileField) {
		$return = true;
		/* 检查文件路径是滞合法 */
		if( !$this->checkFilePath() ) {
			$this->errorMess = $this->getError();
			return false;
		}
		/* 将文件上传的信息取出赋给变量 */
		$name = $_FILES[$fileField]['name'];
		$tmp_name = $_FILES[$fileField]['tmp_name'];
		$size = $_FILES[$fileField]['size'];
		$error = $_FILES[$fileField]['error'];
	
		/* 如果是多个文件上传则$file["name"]会是一个数组 */
		if(is_Array($name)){
			$errors=array();
			/*多个文件上传则循环处理 ， 这个循环只有检查上传文件的作用，并没有真正上传 */
			for($i = 0; $i < count($name); $i++){
				/*设置文件信息 */
				if($this->setFiles($name[$i],$tmp_name[$i],$size[$i],$error[$i] )) {
					if(!$this->checkFileSize() || !$this->checkFileType()){
						$errors[] = $this->getError();
						$return=false;
					}
				}else{
					$errors[] = $this->getError();
					$return=false;
				}
				/* 如果有问题，则重新初使化属性 */
				if(!$return)
					$this->setFiles();
			}
	
			if($return){
				/* 存放所有上传后文件名的变量数组 */
				$fileNames = array();
				/* 如果上传的多个文件都是合法的，则通过销魂循环向服务器上传文件 */
				for($i = 0; $i < count($name); $i++){
					if($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i] )) {
						$this->setNewFileName();
						if(!$this->copyFile()){
							$errors[] = $this->getError();
							$return = false;
						}
						$fileNames[] = $this->newFileName;
					}
				}
				$this->newFileName = $fileNames;
			}
			$this->errorMess = $errors;
			return $return;
			/*上传单个文件处理方法*/
		} else {
			/* 设置文件信息 */
			if($this->setFiles($name,$tmp_name,$size,$error)) {
				/* 上传之前先检查一下大小和类型 */
				if($this->checkFileSize() && $this->checkFileType()){
					/* 为上传文件设置新文件名 */
					$this->setNewFileName();
					/* 上传文件  返回0为成功， 小于0都为错误 */
					if($this->copyFile()){
						return true;
					}else{
						$return=false;
					}
				}else{
					$return=false;
				}
			} else {
				$return=false;
			}
			//如果$return为false, 则出错，将错误信息保存在属性errorMess中
			if(!$return)
				$this->errorMess=$this->getError();
	
			return $return;
		}
	}
	
	/**
	 * 获取上传后的文件名称
	 * @param  void   没有参数
	 * @return string 上传后，新文件的名称， 如果是多文件上传返回数组
	 */
	public function getFileName(){
		return $this->newFileName;
	}
	
	/**
	 * 上传失败后，调用该方法则返回，上传出错信息
	 * @param  void   没有参数
	 * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
	 */
	public function getErrorMsg(){
		return $this->errorMess;
	}
	
	/* 设置上传出错信息 */
	private function getError() {
		$str = "上传文件<font color='red'>{$this->originName}</font>时出错 : ";
		switch ($this->errorNum) {
		    case 4: $str .= "没有文件被上传"; break;
		    case 3: $str .= "文件只有部分被上传"; break;
		    case 2: $str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值"; break;
		    case 1: $str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值"; break;
		    case -1: $str .= "未允许类型"; break;
		    case -2: $str .= "文件过大,上传的文件不能超过{$this->maxsize}个字节"; break;
		    case -3: $str .= "上传失败"; break;
		    case -4: $str .= "建立存放上传文件目录失败，请重新指定上传目录"; break;
		    case -5: $str .= "必须指定上传文件的路径"; break;
		    default: $str .= "未知错误";
		}
		return $str.'<br>';
	}
	
	/* 设置和$_FILES有关的内容 */
	private function setFiles($name="", $tmp_name="", $size=0, $error=0) {
		$this->setOption('errorNum', $error);
		if($error)
			return false;
		$this->setOption('originName', $name);
		$this->setOption('tmpFileName',$tmp_name);
		$aryStr = explode(".", $name);
		$this->setOption('fileType', strtolower($aryStr[count($aryStr)-1]));
		$this->setOption('fileSize', $size);
		return true;
	}
	
	/* 为单个成员属性设置值 */
	private function setOption($key, $val) {
		$this->$key = $val;
	}
	
	/* 设置上传后的文件名称 */
	private function setNewFileName() {
		if ($this->israndname) {
			$this->setOption('newFileName', $this->proRandName());
		} else{
			$this->setOption('newFileName', $this->originName);
		}
	}
	
	/* 检查上传的文件是否是合法的类型 */
	private function checkFileType() {
		if (in_array(strtolower($this->fileType), $this->allowtype)) {
			return true;
		}else {
			$this->setOption('errorNum', -1);
			return false;
		}
	}
	
	/* 检查上传的文件是否是允许的大小 */
	private function checkFileSize() {
		if ($this->fileSize > $this->maxsize) {
			$this->setOption('errorNum', -2);
			return false;
		}else{
			return true;
		}
	}
	
	/* 检查是否有存放上传文件的目录 */
	private function checkFilePath() {
		if(empty($this->path)){
			$this->setOption('errorNum', -5);
			return false;
		}
		if (!file_exists($this->path) || !is_writable($this->path)) {
			if (!@mkdir($this->path, 0755)) {
				$this->setOption('errorNum', -4);
				return false;
			}
		}
		return true;
	}
	
	/* 设置随机文件名 */
	private function proRandName() {
		$fileName = date('YmdHis')."_".rand(100,999);
		return $fileName.'.'.$this->fileType;
	}
	
	/* 复制上传文件到指定的位置 */
	private function copyFile() {
		if(!$this->errorNum) {
			$path = rtrim($this->path, '/').'/';
			$path .= $this->newFileName;
			if (@move_uploaded_file($this->tmpFileName, $path)) {
				return true;
			}else{
				$this->setOption('errorNum', -3);
				return false;
			}
		} else {
			return false;
		}
	}
	
	
    /**
     * 构造函数
     * @param string $fileName 文件名
     */   
    //public function __construct() {
    //    $this->getSrcImageInfo();
    //}
    
    /**
     * 图片上传
     * @access private
     * @return void
     * $img_arr: array(0=>"pjpeg",1=>"jpeg",2=>"jpg",3=>"gif",4=>"png",5=>"bmp"); 允许类型
     * $size   : 图片大小
     * $path   : 图片路径
     * $date   : date("Y-m-d",time()); 长传时间 以此定义文件夹名字
     * $okType ：false  true
     */
    public function displayImageInfo($img_arr,$size,$path,$date,$okType = false) {
        //基本配置
        /* $img_arr	=	array(0=>"pjpeg",1=>"jpeg",2=>"jpg",3=>"gif",4=>"png",5=>"bmp");//允许类型
        $size		=	1000000;//图片大小
        $path		=	"uploadfile/";//图片路径
        $date		=	date("Y-m-d",time());
        $okType		=	false; */
        
        if(is_uploaded_file(@$_FILES['file']['tmp_name'])){
            //返回结果中当前URL
            $url = substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],0,strrpos($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],'/'));
            $upfile		=	@$_FILES["file"];
            $name		=	$upfile["name"];//上传文件的文件名
            $type		=	end(explode(".",$upfile['name']));//获取文件后缀
            $tmp_name	=	$upfile['tmp_name'];
            $error		=	$upfile["error"];//上传后系统返回的值
        
            //验证上传文件类型是否正确
            if(in_array($type,$img_arr)){
                $okType	=	true;
            }else{
                //echo "{'state':'e','msg':'上传文件格式错误'}";
                //exit();
                $result['state'] = "e";
                $result['msg']= "上传文件格式错误";
                return json_encode($result);exit;
            }
            $name		=	time().rand(1000,9999).".".$type;
            $de_path	=	$path.$date;
            $path		=	$de_path.'/'.$name;
            if($upfile['size']>$size){
                $error	=	6;
            }else{
                if($okType){
                    if(!file_exists($de_path)){
                        mkdir($de_path,0777);//目录是否存在	不存在创建当前日期目录
                    }
                    if(!file_exists($path)){
                        move_uploaded_file($tmp_name,$path);//把上传的临时文件移动到指定目录下
                    }
                }
            }
            /**
             * 0:文件上传成功<br/>
             * 1：超过了文件大小，在php.ini文件中设置<br/>
             * 2：超过了文件的大小MAX_FILE_SIZE选项指定的值<br/>
             * 3：文件只有部分被上传<br/>
             * 4：没有文件被上传<br/>
             * 5：上传文件大小为0
             * 6：上传文件过大
             * 7：上传文件格式错误
             */
            $error_arr = array(
                0=>"",
                1=>"超过了文件大小,在php.ini中设置",
                2=>"超过了MAX_FILE_SIZE指定值",
                3=>"文件部分被上传",
                4=>"选择上传文件",
                5=>"文件大小为0",
                6=>"上传文件过大"
            );
        
            if(array_key_exists($error,$error_arr)){
                if($error==0){
                    //echo '{"msg":"http://'.$url.'/'.$path.'","state":"s"}';
                    $result['state'] = "s";
                    $result['msg']= "http://'.$url.'/'.$path.'";
                    return json_encode($result);exit;
                }else{
                    //echo '{"state":"e","msg":"'.$error_arr[$error].'"}';
                    $result['state'] = "e";
                    $result['msg']= "'.$error_arr[$error].'";
                    return json_encode($result);exit;
                }
            }
        }else{
            //echo '{"state":"e","msg":"请上传文件"}';
            $result['state'] = "e";
            $result['msg']= "请上传文件";
            return json_encode($result);exit;
        }
        exit();

    }
}