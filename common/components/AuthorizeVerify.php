<?php

namespace common\components;

use Yii;

/**
 * 用户登陆授权验证处理
 * Class AuthorizeVerify
 * @author  caolong
 * @date    2014-07-05
 */
class AuthorizeVerify {

    //key前缀
    public static $prefixKey = 'lft_users_';
    public static $expire = 14400; //4小时

    /**
     * 设置key前缀
     */

    private function _setPreFixkey() {
        
    }

    /**
     * 设置用户信息
     * @param array $data
     * @return bool
     */
    public static function setUser($data = [], $expire = 14400) {
        $ret = false;
        if (!empty($data)) {
            self::setSession($data);
//            $key = md5(self::$prefixKey . '_' . $data['id']);
////            dprint($key, $data,$expire);
//            $a = json_encode(array('dqwdqd', 'cacfever'));
//            Yii::$app->redis->set($key, 'aaaaaa', $expire);
//            $ret = Yii::$app->redis->get($key);
//            dprint($ret);
        }
        return $ret;
    }

    //同步信息到缓存
    public static function updateUser() {
        $ret = User::model()->findByPk(self::getUser()['id']);
        if (!empty($ret))
            self::setUser($ret->attributes);
    }

    /**
     * 获取用户信息
     * @return bool
     */
    public static function getUser() {
        //$key = self::$prefixKey;
        //$ret = Yii::app()->cache->get($key);
        $ret = self::getSession();
        if (!empty($ret))
            return $ret;
        else
            return false;
    }

    //退出删除用户信息
    public static function delUser() {
        self::delSession();
        $key = self::$prefixKey;
        Yii::app()->cache->delete($key);
    }

    // 保存用户信息 session
    public static function setSession($data = array()) {
//        dprint($data);
        Yii::$app->session['__id'] = $data['id'] ? $data['id'] : '';
        $sessionData = array(
            'userId' => $data->id,
            'userName' => $data->username,
            'groupId' => $data->group_id,
            'super' => $data->group_id == 1 ? 1 : 0,
        );
        Yii::$app->session['userInfo'] = $sessionData;
    }

    /**
     * 更新session
     */
    public static function updataSession($data) {
        if (!empty($data)) {
            $user_info = Yii::app()->session['userInfo'];
            foreach ($data as $k => $v) {
                $user_info[$k] = $v;
            }
            Yii::app()->session['userInfo'] = $user_info;
        }
    }

    // 读取用户信息 session
    public static function getSession() {
        $ret = Yii::$app->session['userInfo'];
        return $ret;
    }

    //清除session session
    public static function delSession() {
        unset(Yii::app()->session['userInfo']);
    }

}
