<?php

/**
 * SettingAction.class.php
 * 系统基本参数
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:23
 * @package  Controller
 * @todo 视图重新写
 */

namespace Admin\Action;

use Think\Action;

class SettingAction extends BaseAction {

    /**
     * doCache
     * 更新缓存
     * @access public
     * @return array
     * @version dogocms 1.1
     */
    public function doCache() {

        //$dirName = './Application/Runtime2';
        $dirName = RUNTIME_PATH;
        $file = new \Org\Util\File();
        $file->clearDir($dirName, false, 'index.html');
        $array = array('status' => 2, 'msg' => '操作成功！');
        echo json_encode($array);
    }

    /**
     * index
     * 系统基本参数
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index() {
        $this->display();
    }

    /**
     * add
     * 系统基本参数添加
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function add() {
        $radios = array(
            'text' => '文本',
            'radio' => '布尔型',
            'textarea' => '多行文本'
        );
        $id = I('get.id');
        $this->assign('id', $id);
        $this->assign('radios', $radios);
        $this->display();
    }

    /**
     * edit
     * 系统基本参数编辑
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function edit() {
        $m = D('Setting');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $data['sys_value'] = htmlspecialchars_decode($data['sys_value']);
        $radios = array(
            'text' => '文本',
            'radio' => '布尔型',
            'textarea' => '多行文本'
        );
        $this->assign('radios', $radios);
        $this->assign('sys_gid', $data['sys_gid']);
        $this->assign('sys_type', $data['sys_type']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * insert
     * 系统基本参数插入数据
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function insert() {
        $m = D('Setting');
        $sys_gid = I('post.sys_gid');
        $sys_name = I('post.sys_name');
        if (empty($sys_gid) || empty($sys_name)) {//不为空说明存在，存在就不能添加
            $this->dmsg('1', '变量名或者所属分组不能为空！', false, true);
        }
        $condition['sys_name'] = array('eq', $sys_name);
        $rs = $m->where($condition)->find();
        if (!empty($rs)) {//不为空说明存在，存在就不能添加
            $this->dmsg('1', '变量名"' . $sys_name . '"已经存在', false, true);
        }
        $data['sys_name'] = $sys_name;
        $data['sys_value'] = I('post.sys_value');
        $data['sys_type'] = $_POST['sys_type'][0];
        $data['updatetime'] = time();
        $data['sys_gid'] = I('post.sys_gid');
        $data['sys_info'] = I('post.sys_info');
        $data['myorder'] = I('post.myorder');
        if ($m->create($data)) {
            $rs = $m->add();
            if ($rs) {
                $this->dmsg('2', '添加成功！', true);
            } else {
                $this->dmsg('1', '操作失败！', false, true);
            }
        }
    }

    /**
     * update
     * 系统基本参数更新
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function update() {
        $m = D('Setting');
        $id = I('post.id');
        $sys_gid = I('post.sys_gid');
        $sys_name = I('post.sys_name');

        $condition['id'] = array('neq', $id);
        if (empty($sys_gid) || empty($sys_name)) {//不为空说明存在，存在就不能添加
            $this->dmsg('1', '变量名或者所属分组不能为空！', false, true);
        }
        $condition['sys_name'] = array('eq', $sys_name);
        $rs = $m->where($condition)->find();
        if (!empty($rs)) {//不为空说明存在，存在就不能添加
            $this->dmsg('1', '变量名"' . $sys_name . '"已经存在', false, true);
        }
        $data['sys_name'] = $sys_name;
        $data['sys_value'] = I('post.sys_value');
        $data['sys_type'] = $_POST['sys_type'][0];
        $data['updatetime'] = time();
        $data['sys_gid'] = I('post.sys_gid');
        $data['sys_info'] = I('post.sys_info');
        $data['myorder'] = I('post.myorder');
        $condition_id['id'] = array('eq', $id);
        $rs2 = $m->where($condition_id)->save($data);
        if ($rs2 == true) {
            $this->dmsg('2', '修改成功！', true);
        } else {
            $this->dmsg('1', '未有当作或者操作失败！', false, true);
        }
    }

    /**
     * settinglist
     * 系统基本参数调取不同id数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function settinglist() {
        $m = D('Setting');
        $id = I('get.id');
        $this->assign('id', $id);
        $this->display('list');
    }

    /**
     * delete
     * 删除信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete() {
        $this->dmsg('1', '暂不支持删除操作！', false, true);
        exit;
        $m = D('Setting');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '删除操作失败！', false, true);
        }//if
    }

    /**
     * listJsonId
     * 取得相应id下的列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function listJsonId() {
        $m = D('Setting');
        $id = I('get.id');
        $condition['sys_gid'] = array('eq', $id);
        $data = $m->where($condition)->select();
        $count = $m->where($condition)->count();
        //$data = $m->select();
        $array = array();
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['sys_type'] == 'radio') {
                    if ($v['sys_value'] == 1) {
                        $v['sys_value'] = '是';
                    } elseif ($v['sys_value'] == 2) {
                        $v['sys_value'] = '否';
                    }
                }
                $array['rows'][] = $v;
            }
        } else {
            $array['rows'] = 0;
        }
        $array['total'] = $count;
        echo json_encode($array);
    }

    /**
     * jsonTree
     * 分类树信息json数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree() {
        $qiuyun = new \Org\Util\Qiuyun;
        $sort = array(
            array('id' => 1, 'text' => '站点设置'),
            array('id' => 2, 'text' => '附件设置'),
            array('id' => 3, 'text' => '信息相关'),
            array('id' => 4, 'text' => '会员设置'),
            array('id' => 5, 'text' => '邮箱设置'),
            array('id' => 6, 'text' => '其它设置')
        );
        $tree = $qiuyun->list_to_tree($sort, 'id', 'parent_id', 'children');
        echo json_encode($tree);
    }

}
