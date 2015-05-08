<?php

/**
 * PageAction.class.php
 * 前台首页
 * 前台单页文件
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace System\Action;
use Think\Action;
class PageAction extends BasesystemAction {

    /**
     * 使用方法/page/index/id/$id
     */
    public function index() {

        $m = M('Pages');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $condition['status'] = array('eq', 20);
        $data = $m->field('ename,id,keywords,description,content')->where($condition)->find();
        if ($data) {
            $data['content'] = stripslashes($data['content']);
        }
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_home = $this->tpl_home;//获取主题皮肤模板名称
        $this->assign('data', $data); // 赋值数据集
        $this->assign('data_sort', $this->getSort());
        $this->assign('title', $data['ename']);
        $this->assign('keywords', $data['keywords']);
        $this->assign('description', $data['description']);
        $this->theme($skin)->display($tpl_home . 'page');
    }

    /**
     * 获取单页分类信息
     */
    public function getSort() {
        $m = M('PagesSort');
        $condition['status'] = array('eq', 20);
        $condition['parent_id'] = array('eq', 0);
        $data = $m->field('id,ename')->where($condition)->select();
        if ($data) {
            $p = M('Pages');
            foreach ($data as $k => $v) {
                $condition_page['sort_id'] = array('eq', $v['id']);
                $data[$k]['list'] = $p->field('ename,id,keywords,description,content')->where($condition_page)->select();
            }
        }
        return $data;
    }

}
