<?php

/**
 * IndexAction.class.php
 * 前台首页
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */

namespace Home\Action;

use Think\Action;

class IndexAction extends BasehomeAction {

    public function index() {
        $m = D('Setting');
        $title['sys_name'] = array('eq', 'cfg_title');
        $keywords['sys_name'] = array('eq', 'cfg_keywords');
        $description['sys_name'] = array('eq', 'cfg_description');
        $data_title = $m->where($title)->find();
        $data_keywords = $m->where($keywords)->find();
        $data_description = $m->where($description)->find();

        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_home = $this->tpl_home; //获取主题皮肤模板名称
        $this->assign('title', $data_title['sys_value']);
        $this->assign('keywords', $data_keywords['sys_value']);
        $this->assign('description', $data_description['sys_value']);
        $this->theme($skin)->display($tpl_home . 'index');
    }

}
