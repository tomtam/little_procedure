<?php

namespace campaign\components;

use Yii;
use yii\base\Exception;

/**
 * 系统助手类
 *
 */
class XUtils {
    /**
    * @date: 2017年2月19日 下午7:02:50
    * @author: louzhiqiang
    * @return:
    * @desc:   获得设备的随机数
    */
    public static function getURandom($min = 0, $max = 0x7FFFFFFF){
        $diff = $max - $min;
        if ($diff > PHP_INT_MAX) {
            throw new Exception('Bad Range');
        }
        
        $fh = fopen('/dev/urandom', 'r');
        stream_set_read_buffer($fh, PHP_INT_SIZE);
        $bytes = fread($fh, PHP_INT_SIZE );
        if ($bytes === false || strlen($bytes) != PHP_INT_SIZE ) {
            //throw new RuntimeException("nable to get". PHP_INT_SIZE . "bytes");
            return 0;
        }
        fclose($fh);
        
        if (PHP_INT_SIZE == 8) { // 64-bit versions
            list($higher, $lower) = array_values(unpack('N2', $bytes));
            $value = $higher << 32 | $lower;
        }
        else { // 32-bit versions
            list($value) = array_values(unpack('Nint', $bytes));
        
        }
        
        $val = $value & PHP_INT_MAX;
        $fp = (float)$val / PHP_INT_MAX; // convert to [0,1]
        
        return (int)(round($fp * $diff) + $min);
    }
    /**
     * 友好显示var_dump
     */
    static public function dump($var, $echo = true, $label = null, $strict = true) {
        $label = ( $label === null ) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo $output;
            return null;
        } else
            return $output;
    }

    /**
     * 获取客户端IP地址
     */
    static public function getClientIP() {
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $ip = ( false !== ip2long($ip) ) ? $ip : '0.0.0.0';
        return $ip;
    }
        
    /**
     * 循环创建目录
     */
    static public function mkdir($dir, $mode = 0777) {
        if (is_dir($dir) || @mkdir($dir, $mode))
            return true;
        if (!mk_dir(dirname($dir), $mode))
            return false;
        return @mkdir($dir, $mode);
    }

    /**
     * 格式化单位
     */
    static public function byteFormat($size, $dec = 2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos ++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }
    /**
     * 查询字符生成
     */
    static public function buildCondition(array $getArray, array $keys = array()) {
        if ($getArray) {
            $arr = [];
            foreach ($getArray as $key => $value) {
                if (in_array($key, $keys) && $value) {
                    $arr[$key] = CHtml::encode(strip_tags($value));
                }
            }
            return $arr;
        }
    }

    /**
     * base64_encode
     */
    static function b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    /**
     * base64_decode
     */
    static function b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * 验证邮箱
     */
    public static function email($str) {
        if (empty($str))
            return true;
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($str, '@') !== false && strpos($str, '.') !== false) {
            if (preg_match($chars, $str)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 验证手机号码
     */
    public static function mobile($str) {
        if (empty($str)) {
            return true;
        }

        return preg_match('#^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^18[0-9]\d{8}$#', $str);
    }

    /**
     * 验证固定电话
     */
    public static function tel($str) {
        if (empty($str)) {
            return true;
        }
        return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', trim($str));
    }

    /**
     * 验证qq号码
     */
    public static function qq($str) {
        if (empty($str)) {
            return true;
        }

        return preg_match('/^[1-9]\d{4,12}$/', trim($str));
    }

    /**
     * 验证邮政编码
     */
    public static function zipCode($str) {
        if (empty($str)) {
            return true;
        }

        return preg_match('/^[1-9]\d{5}$/', trim($str));
    }

    /**
     * 验证ip
     */
    public static function ip($str) {
        if (empty($str))
            return true;

        if (!preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $str)) {
            return false;
        }

        $ip_array = explode('.', $str);

        //真实的ip地址每个数字不能大于255（0-255）
        return ( $ip_array[0] <= 255 && $ip_array[1] <= 255 && $ip_array[2] <= 255 && $ip_array[3] <= 255 ) ? true : false;
    }

    /**
     * 验证身份证(中国)
     */
    public static function idCard($str) {
        $str = trim($str);
        if (empty($str))
            return true;

        if (preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i", $str))
            return true;
        else
            return false;
    }

    /**
     * 验证网址
     */
    public static function url($str) {
        if (empty($str))
            return true;

        return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $str) ? true : false;
    }
    /**
     * 拆分sql
     *
     * @param $sql
     */
    public static function splitsql($sql) {
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=" . Yii::app()->db->charset, $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num ++;
        }
        return ($ret);
    }

    /**
     * 字符截取
     *
     * @param $string
     * @param $length
     * @param $dot
     */
    public static function cutstr($string, $length, $dot = '...', $charset = 'utf-8') {
        if (strlen($string) <= $length)
            return $string;

        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

        $strcut = '';
        if (strtolower($charset) == 'utf-8') {

            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {

                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || ( 32 <= $t && $t <= 126 )) {
                    $tn = 1;
                    $n ++;
                    $noc ++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n ++;
                }

                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i ++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }

        $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        $pos = strrpos($strcut, chr(1));
        if ($pos !== false) {
            $strcut = substr($strcut, 0, $pos);
        }
        return $strcut . $dot;
    }

    /**
     * 描述格式化
     * @param  $subject
     */
    public static function clearCutstr($subject, $length = 0, $dot = '...', $charset = 'utf-8') {
        if ($length) {
            return XUtils::cutstr(strip_tags(str_replace(array("\r\n"), '', $subject)), $length, $dot, $charset);
        } else {
            return strip_tags(str_replace(array("\r\n"), '', $subject));
        }
    }

    /**
     * 检测是否为英文或英文数字的组合
     *
     * @return unknown
     */
    public static function isEnglist($param) {
        if (!eregi("^[A-Z0-9]{1,26}$", $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 将自动判断网址是否加http://
     *
     * @param $http
     * @return  string
     */
    public static function convertHttp($url) {
        if ($url == 'http://' || $url == '')
            return '';

        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://')
            $str = 'http://' . $url;
        else
            $str = $url;
        return $str;
    }

    // 自动转换字符集 支持数组转换
    static public function autoCharset($string, $from = 'gbk', $to = 'utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
            //如果编码相同或者非字符串标量则不转换
            return $string;
        }
        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } elseif (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);
                if ($key != $_key)
                    unset($string[$key]);
            }
            return $string;
        } else {
            return $string;
        }
    }

    /*
      标题样式恢复
     */

    public static function titleStyleRestore($serialize, $scope = 'bold') {
        $unserialize = unserialize($serialize);
        if ($unserialize['bold'] == 'Y' && $scope == 'bold')
            return 'Y';
        if ($unserialize['underline'] == 'Y' && $scope == 'underline')
            return 'Y';
        if ($unserialize['color'] && $scope == 'color')
            return $unserialize['color'];
    }

    /**
     * 列出文件夹列表
     *
     * @param $dirname
     * @return unknown
     */
    public static function getDir($dirname) {
        $files = array();
        if (is_dir($dirname)) {
            $fileHander = opendir($dirname);
            while (( $file = readdir($fileHander) ) !== false) {
                $filepath = $dirname . '/' . $file;
                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0 || is_file($filepath)) {
                    continue;
                }
                $files[] = self::autoCharset($file, 'GBK', 'UTF8');
            }
            closedir($fileHander);
        } else {
            $files = false;
        }
        return $files;
    }

    /**
     * 列出文件列表
     *
     * @param $dirname
     * @return unknown
     */
    public static function getFile($dirname) {
        $files = array();
        if (is_dir($dirname)) {
            $fileHander = opendir($dirname);
            while (( $file = readdir($fileHander) ) !== false) {
                $filepath = $dirname . '/' . $file;

                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0 || is_dir($filepath)) {
                    continue;
                }
                $files[] = self::autoCharset($file, 'GBK', 'UTF8');
                ;
            }
            closedir($fileHander);
        } else {
            $files = false;
        }
        return $files;
    }

    /**
     * [格式化图片列表数据]
     *
     * @return [type] [description]
     */
    public static function imageListSerialize($data) {

        foreach ((array) $data['file'] as $key => $row) {
            if ($row) {
                $var[$key]['fileId'] = $data['fileId'][$key];
                $var[$key]['file'] = $row;
            }
        }
        return array('data' => $var, 'dataSerialize' => empty($var) ? '' : serialize($var));
    }

    /**
     * 反引用一个引用字符串
     * @param  $string
     * @return string
     */
    static function stripslashes($string) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::stripslashes($val);
            }
        } else {
            $string = stripslashes($string);
        }
        return $string;
    }

    /**
     * 引用字符串
     * @param  $string
     * @param  $force
     * @return string
     */
    static function addslashes($string, $force = 1) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::addslashes($val, $force);
            }
        } else {
            $string = addslashes($string);
        }
        return $string;
    }
    /*
     * 生成字符串
     */

    static function randpw($len = 6, $format = 'ALL') {
        $is_abc = $is_numer = 0;
        $password = $tmp = '';
        switch ($format) {
            case 'ALL':
                $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        } // www.jb51.net
        mt_srand((double) microtime() * 1000000 * getmypid());
        while (strlen($password) < $len) {
            $tmp = substr($chars, (mt_rand() % strlen($chars)), 1);
            if (($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 ) || $format == 'CHAR') {
                $is_numer = 1;
            }
            if (($is_abc <> 1 && preg_match('/[a-zA-Z]/', $tmp)) || $format == 'NUMBER') {
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if ($is_numer <> 1 || $is_abc <> 1 || empty($password)) {
            $password = self::randpw($len, $format);
        }
        return $password;
    }

    /*
     * 生成密码
     */

    public static function createPass() {
        $data = [];
        $data['password_hash'] = uniqid();
        $data['password'] = XUtils::randpw();
        $data['pay_password'] = XUtils::randpw(6, 'NUMBER');
        return $data;
    }

    public static function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
 /*
     * 金额中文转换
     */

  static function actionNum_to_rmb($num) {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 100) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int) $num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        } else {
            return $c . "整";
        }
    }

    /*
     * 回执单图片编辑
     * */

   static function Image($info) {
        $strcode = XUtils::randpw(30, 'ALL');
        $str = XUtils::randpw(10, 'NUMBER');
        $code = time() . $strcode;
        $odd = date('YmdHis', time()) . $str;
        $im = new \Imagick("static/common/images/1.jpg");
        $im->thumbnailImage(800, null);
        $im->borderImage(new \ImagickPixel("white"), 5, 5);
        $time = time();
        $draw = new \ImagickDraw();
        $draw->setFont('static/resources/DroidSansFallback.ttf');
        $im->annotateImage($draw, 97, 91, 0, $odd); //付款户名
        $im->annotateImage($draw, 216, 127, 0, $info['name']); //付款户名
        $im->annotateImage($draw, 581, 127, 0, '北京理房通支付科技有限公司'); //收款户名
        $im->annotateImage($draw, 217, 165, 0, $info['email']); //付款账号
        $im->annotateImage($draw, 581, 165, 0, $info['num']); //收款账号
        $im->annotateImage($draw, 217, 198, 0, '北京理房通支付科技有限公司'); //付款开户机构
        $im->annotateImage($draw, 581, 198, 0, $info['account']); //收款开户机构
        $im->annotateImage($draw, 141, 233, 0, $info['money']); //金额
        $money = "人民币" . XUtils::actionNum_to_rmb(str_replace(',','',$info['money']));
        $im->annotateImage($draw, 141, 273, 0, $money); //金额大写

        $im->annotateImage($draw, 141, 318, 0, $info['starttime']); //开始日期
        $im->annotateImage($draw, 200, 318, 0, '到');
        $im->annotateImage($draw, 223, 318, 0, $info['endtime']); //结束日期
        $im->annotateImage($draw, 315, 318, 0, '手续费');
        $im->annotateImage($draw, 219, 395, 0, $code); //验证码
        $im->annotateImage($draw, 692, 451, 0, $info['endtime']); //记账日期
        $imgname = $time. XUtils::randpw(5, 'ALL');
        file_put_contents('/nfs/lft_shanghu/op/' . $imgname . '.jpg', $im);
        $result = array(
            'code' => $code,
            'odd' => $odd,
            'url' => "/nfs/lft_shanghu/op/" . $imgname . '.jpg'
        );
        return $result;
        exit;
    }
    /*
     * 密码验证规则，包含字母，数字，字符中的2个
     */

    public static function passWordRule($pass) {
        if (preg_match('/^(?![0-9]+$)(?![\W]+$)(?![a-z]+$)(?![A-Z]+$)[a-zA-Z0-9\W_]{8,20}+$/', $pass)) {
            return true;
        }
        return false;
    }
	//生成订单号	
	public static function build_order_no(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    public static function userPassRule($pass) {
    	return self::passWordRule($pass);
/*     	if (preg_match('/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~][\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{8,15}$/', $pass)) {
    		return true;
    	}
    	return false; */
    }
    /**
    * @date: 2016年12月10日 下午2:43:35
    * @author: louzhiqiang
    * @return:
    * @desc:   二维数组排序
    */
    public static function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    static function get_client_ip() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }
    /**
     * 去除字符串中的空格
     * @param unknown $str
     * @return mixed
     */
    static function qukong($str) {
        $qian = array(" ", "　", "\t", "\n", "\r");
        $hou = array("", "", "", "", "");
        return str_replace($qian, $hou, $str);
    }
    /**
     * @name   sms
     * @author Chiwm
     * @time   2015-03-30
     * @功能：    短信接口
     * @return array
     */
    static function sms($phone, $content) {
        $phone_zhengze = "/^1(3|4|5|6|7|8)\d{9}$/";
        if (isset($phone) && !empty($phone)){
            if(!preg_match($phone_zhengze, $phone)){
                $result['result'] = '0';
                $result['resultMessage'] = '手机号码格式不正确';
                return $result;exit;
            }else if( !empty($content)){
                $data_array = array(
                    'num'             => $phone,
                    'messageContent'  => $content
                );
                $sms_message = LftHttp::http_post ( Yii::$app->params['lft_sms_api'], $data_array ); //调取java短信接口  LFT_CORE_PERSONAL . 'mobile/sendMessageOffund'
                return json_decode ( $sms_message ['FILE'], true );
            }else{
                $result['result'] = '0';
                $result['resultMessage'] = '短信内容不能为空';
                return $result;exit;
            }
        }else{
            $result['result'] = '0';
            $result['resultMessage'] = '手机号码不能为空';
            return $result;exit;
        }
    }
}

?>
