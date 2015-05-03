<?php

/**
 * MessageAction.class.php
 * 留言信息
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class MessageAction extends BaseAction {

    /**
     * index
     * 列表页
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
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
        $m = M('Message');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->assign('data', $data);
        $this->assign('v_status', $data['status']);
        $this->display();
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
        $m = M('Message');
        $sort_id = I('post.sort_id');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['replaytime'] = time();
        $data['status'] = $_POST['status'][0];
        $data['updatetime'] = time();
        $data['replycontent'] = I('post.replycontent');
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * 留言删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = M('Message');
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
     * 留言分类
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
     * 添加留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortadd()
    {
        $status = array(
            '20' => '启用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->display();
    }

    /**
     * sortedit
     * 编辑留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortedit()
    {
        $m = M('MessageSort');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '启用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * sortinsert
     * 写入留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortinsert()
    {
        $m = M('MessageSort');
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
     * 更新留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortupdate()
    {
        $m = M('MessageSort');
        $id = I('post.id');
        $ename = I('post.ename');
        $condition['id'] = array('neq', $id);
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
        $condition_id['id'] = array('eq', $id);
        $rs = $m->where($condition_id)->save($data);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sortdelete
     * 删除留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortdelete()
    {
        $m = M('MessageSort');
        $l = M('Message');
        $id = I('post.id');
        $condition['sort_id'] = array('eq', $id);
        if ($l->field('id')->where($condition)->find()) {
            $this->dmsg('1', '列表中含有该分类的信息，不能删除！', false, true);
        }
        $condition_id['id'] = array('eq', $id);
        $del = $m->where($condition_id)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
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
        $m = M('Message');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->join(' join ' . C('DB_PREFIX') . 'message m')
                        ->join(C('DB_PREFIX') . 'message_sort ms ON ms.id=m.id')
                        ->limit($firstRow . ',' . $pageRows)->order('m.id desc')->select();
        $array = array();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                $data[$k]['replytime'] = date('Y-m-d H:i:s', $v['replytime']);
                if ($v['status'] == '20') {
                    $data[$k]['status'] = '可用';
                } else {
                    $data[$k]['status'] = '禁用';
                }
            }
        } else {
            $data = array();
        }
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

    /**
     * sortJson
     * 返回sortjson模型分类数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortJson()
    {
        $m = M('MessageSort');
        $list = $m->select();
        $count = $m->count("id");
        $a = array();
        $array = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $a[$k] = $v;
                if ($v['status'] == '20') {
                    $a[$k]['status'] = '可用';
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
        $m = M('MessageSort');
        $tree = $m->field(array('id', 'ename' => 'text'))->select();
        $tree = $qiuyun->list_to_tree($tree, 'id', 'parent_id', 'children');
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
    }

}

?>