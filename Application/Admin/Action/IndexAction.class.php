<?php

/**
 * IndexAction.class.php
 * 后台文件
 * 后台核心文件，登录后跳转页面
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:13
 * @package  Controller
 * @todo 权限验证
 */
namespace Admin\Action;
use Think\Action;
class IndexAction extends BaseAction {

    /**
     * index
     * 后台首页方法
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function index()
    {
        require_once(APP_PATH . '/Admin/Conf/leftermenu.php'); //引入菜单
        foreach ($array as $key => $val) {
            foreach ($val['items'] as $skey => $sval) {
                foreach ($sval['items'] as $sskey => $ssval) {
                    if ($ssval['type'] == 'abc') {//echo '<pre>';print_r(c);//exit();
                        //unset($array[$key]['items'][$sskey]['items'][$sskey]);
                    }
                }
            }
        }
        $this->assign('menu', $array);
        $this->display();
    }

}
