<?php

/**
 * BasememberAction.class.php
 * 前台页面公共方法
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace User\Action;
use Think\Action;
class BaseuserAction extends BasecommAction {

    //初始化
    function _initialize() {
        //此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        $status = session('LOGIN_M_STATUS');
        if ($status != 'TRUE') {
            $this->redirect('..' . __MODULE__ . '/Passport/login');
        }
        parent::_initialize();//继承父级
    }

   

}

?>
