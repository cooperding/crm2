<?php

/**
 * MembersAction.class.php
 * 会员中心
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-1-5 14:57
 * @package  Controller
 * @todo 信息各项操作
 */
namespace Admin\Action;
use Think\Action;
class MembersAction extends BaseAction {

    /**
     * index
     * 信息列表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }

    /**
     * add
     * 添加会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function add()
    {
        $status = array(
            '20' => '启用',
            '10' => '禁用'
        );
        $sex = array(
            '10' => '男',
            '11' => '女',
            '12' => '保密'
        );
        $is_recycle = array(
            '20' => '是',
            '10' => '否'
        );
        $this->assign('status', $status);
        $this->assign('is_recycle', $is_recycle);
        $this->assign('sex', $sex);
        $this->display();
    }

    /**
     * edit
     * 编辑会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function edit()
    {
        $m = D('Members');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '启用',
            '10' => '禁用'
        );
        $sex = array(
            '10' => '男',
            '11' => '女',
            '12' => '保密'
        );
        $is_recycle = array(
            '20' => '是',
            '10' => '否'
        );
        $this->assign('data', $data);
        $this->assign('status', $status);
        $this->assign('is_recycle', $is_recycle);
        $this->assign('sex', $sex);
        $this->assign('v_status', $data['status']);
        $this->assign('v_is_recycle', $data['is_recycle']);
        $this->assign('v_sex', $data['sex']);
        $this->display();
    }

    /**
     * insert
     * 分类插入数据
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = D('Members');
        $user_name = I('post.username');
        $email = I('post.email');
        $password = I('post.password');
        if (empty($user_name)) {
            $this->dmsg('1', '用户名不能为空！', false, true);
        }
        if (empty($email)) {
            $this->dmsg('1', '邮箱不能为空！', false, true);
        }
        if (empty($password)) {
            $this->dmsg('1', '密码不能为空！', false, true);
        }
        $condition_name['username'] = array('eq', $user_name);
        $rs_name = $m->where($condition_name)->find();
        if ($rs_name) {
            $this->dmsg('1', '用户名已经存在！', false, true);
        }
        $condition_email['email'] = array('eq', $email);
        $rs_email = $m->where($condition_email)->find();
        if ($rs_email) {
            $this->dmsg('1', '邮箱已经存在！', false, true);
        }
        $data['password'] = R('Common/System/getPwd', array($user_name, $password));
        $data['username'] = $user_name;
        $data['email'] = $email;
        $data['sex'] = $_POST['sex'][0];
        $data['status'] = $_POST['status'][0];
        $data['remark'] = I('post.remark');
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['ip'] = get_client_ip();
        
        $rs = $m->add($data);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * insert
     * 分类插入数据
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function update()
    {
        $m = D('Members');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $user_name = I('post.username');
        $email = I('post.email');
        $password = I('post.password');
        if (empty($user_name)) {
            $this->dmsg('1', '用户名不能为空！', false, true);
        }
        if (empty($email)) {
            $this->dmsg('1', '邮箱不能为空！', false, true);
        }
        $condition_name['id'] = array('neq', $id);
        $condition_name['username'] = array('eq', $user_name);
        $rs_name = $m->where($condition_name)->find();
        if ($rs_name) {
            $this->dmsg('1', '用户名已经存在！', false, true);
        }
        $condition_email['id'] = array('neq', $id);
        $condition_email['email'] = array('eq', $email);
        $rs_email = $m->where($condition_email)->find();
        if ($rs_email) {
            $this->dmsg('1', '邮箱已经存在！', false, true);
        }
        if (!empty($password)) {
            $data['password'] = R('Common/System/getPwd', array($user_name, $password));
        } else {
            unset($data['password']);
        }
        $data['username'] = $user_name;
        $data['email'] = $email;
        $data['sex'] = $_POST['sex'][0];
        $data['status'] = $_POST['status'][0];
        $data['remark'] = I('post.remark');
        $data['updatetime'] = time();
        
        
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '未有操作或操作失败！', false, true);
        }
    }

    /**
     * delete
     * 分类信息删除操作
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = D('Members');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * listJsonId
     * 取得field信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function listJsonId()
    {
        $m = D('Members');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $title = $_REQUEST['title'];
        if ($title) {
            $condition['username|email'] = array('like', '%' . $title . '%');
        }
        $count = $m->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->where($condition)->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if ($v['status'] == '20') {
                    $data[$k]['status'] = '启用';
                } elseif ($v['status'] == '10') {
                    $data[$k]['status'] = '禁用';
                }
            }
        } else {
            $count = 0;
            $data = array();
        }
        $array = array();
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

}

?>