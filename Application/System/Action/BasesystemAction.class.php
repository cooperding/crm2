<?php

/**
 * BasehomeAction.class.php
 * 前台页面公共方法
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */

namespace System\Action;

use Think\Action;

class BasesystemAction extends BasecommAction {

    //初始化
    function _initialize() {
        parent::_initialize(); //继承父级
        //检测是否登录
        if (session('LOGIN_SYS_STATUS') != 'TRUE') {
            redirect(__MODULE__ . '/Passport'); //跳转到登录网关
            exit;
        }
        
    }

}

?>
