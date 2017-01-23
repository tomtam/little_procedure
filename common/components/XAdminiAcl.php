<?php

namespace common\components;
use common\components\AuthorizeVerify;

/**
 * 后台菜单及权限
 * 
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Acl
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class XAdminiAcl {

    //权限配制数据
    public static $aclList = array(
        '首页' => array(
            'controller' => 'home', 'url' => 'default/home', 'acl' => 'home', 'action' => array(
                array('name' => '系统首页', 'url' => 'default/home', 'acl' => 'home_index', 'list_acl' => array()),
            )
        ),
       
        '用户' => array(
            'controller' => 'user', 'url' => 'admin/index', 'acl' => 'user', 'action' => array(
                array('name' => '管理员列表', 'url' => 'admin/index', 'acl' => 'admin_index', 'list_acl' => array(
                        '录入' => 'admin_create', '编辑' => 'admin_update', '删除' => 'admin_delete'
                    )),
                array('name' => '管理员权限', 'url' => 'admin/group', 'acl' => 'admin_group', 'list_acl' => array(
                        '录入' => 'admin_group_create', '编辑' => 'admin_group_update', '删除' => 'admin_group_delete'
                    )),
                array('name' => '管理员日志', 'url' => 'logger/admin', 'acl' => 'admin_logger', 'list_acl' => array(
                        '删除' => 'admin_logger_delete'
                    )),
//                array('name' => '留言反馈', 'url' => 'question/index', 'acl' => 'question_index', 'list_acl' => array(
//                        '回复' => 'question_update', '删除' => 'question_delete'
//                    )),
            )
        ),
      
//        '工具' => array(
//            'controller' => 'tools', 'url' => 'database/index', 'acl' => 'tools', 'action' => array(
//                array('name' => '数据库管理', 'url' => 'database/index', 'acl' => 'database_index', 'list_acl' => array(
//                        '执行sql' => 'database_query', '数据库备份' => 'database_export', '数据库还原' => 'database_import', '备份文件下载' => 'database_download', '删除备份文件' => 'database_delete',
//                    )),
//                array('name' => '缓存管理', 'url' => 'tools/cache', 'acl' => 'tools_cache', 'list_acl' => array()),
//            //array('name'=>'程序升级','url'=>'upgrade/index','acl'=>'upgrade/index','list_acl'=>array()),
//            )
//        )
    );

    /**
     * 后台菜单过滤
     *
     */
    static public function filterMenu($append = ',home,home_index') {
        $admini = AuthorizeVerify::getUser();
//        dprint($admini);
        $groupId = $admini['groupId'];
        if ($groupId != 1) {
            $aclModel = AdminGroup::model()->findByPk($groupId);
            $acl = $aclModel->acl . $append;
            $aclArr = explode(',', $acl);
            foreach (self::$aclList as $k => $r) {
                if (!in_array($r['acl'], $aclArr)) {
                    unset(self::$aclList[$k]);
                } else {
                    self::$aclList[$k]['url'] = self::_parentRouter($k, $aclArr);
                    foreach ($r['action'] as $kk => $rr) {
                        if (!in_array($rr['acl'], explode(',', $acl)))
                            unset(self::$aclList[$k]['action'][$kk]);
                    }
                }
            }
        }
        return self::$aclList;
    }

    /**
     * 取大类链接，防止有未授权情况
     * @param string $n
     * @param array $acl
     * @return string
     */
    private static function _parentRouter($n, $acl) {
        $one = 0;
        foreach ((array) self::$aclList[$n]['action'] as $key => $row) {
            if (in_array($row['acl'], $acl)) {
                if ($one == 0)
                    return $row['url'];
            }
        }
        return 'home';
    }

}
