<?php

/**
 * BlockAction.class.php
 * 碎片信息
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class BlockAction extends BaseAction {

    /**
     * index
     * 碎片列表页
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
     * 添加信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function add()
    {
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->display();
    }

    /**
     * edit
     * 编辑信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function edit()
    {
        $m = M('BlockList');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * insert
     * 写入碎片信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = M('BlockList');
        $data['title'] = I('post.title');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['title'])) {
            $this->dmsg('1', '名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['url'] = I('post.url');
        $data['title_img'] = I('post.title_img');
        $data['description'] = I('post.description');
        $data['myorder'] = I('post.myorder');
        $data['field1'] = I('post.field1');
        $data['field2'] = I('post.field2');
        $data['field3'] = I('post.field3');
        $data['field4'] = I('post.field4');
        $data['field5'] = I('post.field5');
        $data['updatetime'] = time();
        $data['status'] = $_POST['status'][0];
        if ($m->create()) {
            $rs = $m->add($data);
            if ($rs) {//存在值
                $this->dmsg('2', '操作成功！', true);
            } else {
                $this->dmsg('1', '操作失败！', false, true);
            }
        } else {
            $this->dmsg('1', '根据表单提交的POST数据创建数据对象失败！', false, true);
        }
    }

    /**
     * update
     * 更新信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function update()
    {
        $m = M('BlockList');
        $id = I('post.id');
        $data['title'] = I('post.title');
        $data['sort_id'] = I('post.sort_id');
        $condition['id'] = array('eq', $id);
        if (empty($data['title'])) {
            $this->dmsg('1', '名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['url'] = I('post.url');
        $data['title_img'] = I('post.title_img');
        $data['description'] = I('post.description');
        $data['myorder'] = I('post.myorder');
        $data['field1'] = I('post.field1');
        $data['field2'] = I('post.field2');
        $data['field3'] = I('post.field3');
        $data['field4'] = I('post.field4');
        $data['field5'] = I('post.field5');
        $data['updatetime'] = time();
        $data['status'] = $_POST['status'][0];
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * delete删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = M('BlockList');
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
     * sort
     * 碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sort()
    {
        $this->display();
    }

    /**
     * sortadd
     * 添加碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortAdd()
    {
        $status = array(
            '20' => ' 启用 ',
            '10' => ' 禁用 '
        );
        $this->assign('status', $status);
        $this->display();
    }

    /**
     * sortedit
     * 编辑碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortEdit()
    {
        $m = M('BlockSort');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => ' 启用 ',
            '10' => ' 禁用 '
        );
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * sortinsert
     * 写入碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortInsert()
    {
        $m = M('BlockSort');
        $ename = I('post.ename');
        $condition['ename'] = array('eq', $ename);
        if (empty($ename)) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        if ($m->field('id')->where($condition)->find()) {
            $this->dmsg('1', '您输入的名称' . $ename . '已经存在！', false, true);
        }
        $data['ename'] = $ename;
        $data['status'] = $_POST['status'][0];
        $data['updatetime'] = time();
        if ($m->create()) {
            $rs = $m->add($data);
            if ($rs) {//存在值
                $this->dmsg('2', '操作成功！', true);
            } else {
                $this->dmsg('1', '操作失败！', false, true);
            }
        } else {
            $this->dmsg('1', '根据表单提交的POST数据创建数据对象失败！', false, true);
        }
    }

    /**
     * sortupdate
     * 更新碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortUpdate()
    {
        $m = M('BlockSort');
        $id = I('post.id');
        $ename = I('post.ename');
        $condition['ename'] = array('eq', $ename);
        $condition['id'] = array('neq', $id);
        if (empty($ename)) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        if ($m->field('id')->where($condition)->find()) {
            $this->dmsg('1', '您输入的名称' . $ename . '已经存在！', false, true);
        }
        $condition_id['id'] = array('eq', $id);
        $data['ename'] = $ename;
        $data['status'] = $_POST['status'][0];
        $data['updatetime'] = time();
        $rs = $m->where($condition_id)->save($data);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sortdelete
     * 删除碎片分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortDelete()
    {
        $m = M('BlockSort');
        $l = M('Block');
        $id = I('post.id');
        $condition_sort['sort_id'] = array('eq', $id);
        if ($l->field('id')->where($condition_sort)->find()) {
            $this->dmsg('1', '列表中含有该分类的信息，不能删除！', false, true);
        }
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sortJson
     * 返回sortjson模型分类数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonSortList()
    {
        $m = M('BlockSort');
        $list = $m->order('id desc')->select();
        $count = $m->count("id");
        $a = array();
        $array = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $a[$k] = $v;
                if ($v['status'] == '20') {
                    $a[$k]['status'] = '启用';
                } else {
                    $a[$k]['status'] = '禁用';
                }
            }
        }
        $array['total'] = $count;
        $array['rows'] = $a;
        echo json_encode($array);
    }

    /**
     * jsonTree
     * 头部导航返回树形json数据
     * @access add edit
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree()
    {
        $qiuyun = new \Org\Util\Qiuyun;
        $m = M('BlockSort');
        $condition['status'] = array('eq', '20');
        $tree = $m->field(array('id', 'ename' => 'text'))->where($condition)->select();
        //$tree = $qiuyun->list_to_tree($tree, 'id', 'parent_id', 'children');
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
    }

    /**
     * jsonList
     * 取得列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonList()
    {
        $m = M('BlockList');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $title = $_REQUEST['title'];
        if ($title) {
            $condition['title'] = array('like', '%' . $title . '%');
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
                } else {
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