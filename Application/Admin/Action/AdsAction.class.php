<?php

/**
 * AdsAction.class.php
 * 广告信息
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class AdsAction extends BaseAction {

    /**
     * index
     * 广告列表页 
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }

    /**
     * newslist
     * 信息列表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function newslist()
    {
        $id = I('get.id');
        $this->assign('id', $id);
        $this->display('newslist');
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
        $this->assign('id',I('get.id'));
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
        $m = M('Ads');
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
     * insert
     * 插入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = M('Ads');
        $data['name'] = I('post.name');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['name'])) {
            $this->dmsg('1', '广告名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属广告分类！', false, true);
        }
        $data['url'] = I('post.url');
        $data['status'] = $_POST['status'][0];
        $data['pic'] = I('post.pic');
        $data['remark'] = I('post.remark');
        $data['myorder'] = I('post.myorder');
        $data['addtime'] = time();
        $data['updatetime'] = time();
        if ($m->create($data)) {
            $rs = $m->add();
            if ($rs == true) {
                $this->dmsg('2', ' 操作成功！', true);
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
        $m = M('Ads');
        $condition['id'] = array('eq',I('post.id'));
        $data['name'] = I('post.name');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['name'])) {
            $this->dmsg('1', '广告名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属广告分类！', false, true);
        }
        $data['url'] = I('post.url');
        $data['status'] = $_POST['status'][0];
        $data['pic'] = I('post.pic');
        $data['remark'] = I('post.remark');
        $data['myorder'] = I('post.myorder');
        $data['addtime'] = time();
        $data['updatetime'] = time();
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
        $m = M('Ads');
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
     * 广告分类
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
     * 添加广告分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortadd()
    {
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->display();
    }

    /**
     * sortedit
     * 编辑广告分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortedit()
    {
        $m = M('AdsSort');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * sortinsert
     * 写入广告分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortinsert()
    {
        $m = M('AdsSort');
        $data['ename'] = I('post.ename');
        $condition['ename'] = array('eq', $data['ename']);
        if (empty($data['ename'])) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
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
     * 更新广告分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortupdate()
    {
        $m = M('AdsSort');
        $id = I('post.id');
        $data['ename'] = I('post.ename');
        $condition['ename'] = array('eq', $ename);
        $condition['id'] = array('neq', $id);
        if (empty($data['ename'])) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        if ($m->field('id')->where($condition)->find()) {
            $this->dmsg('1', '您输入的名称' . $ename . '已经存在！', false, true);
        }
        $condition_id['id'] = array('eq',$id);
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
     * 删除广告分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortdelete()
    {
        $m = M('AdsSort');
        $l = M('Ads');
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
    public function sortJson()
    {
        $m = M('AdsSort');
        $list = $m->select();
        $count = $m->count("id");
        $a = array();
        $array = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $a[$k] = $v;
                if ($v['status'] == '20') {
                    $a[$k]['status'] = '启用';
                } elseif ($v['status'] == '10') {
                    $a[$k]['status'] = '禁用';
                }
            }
        }
        $array['total'] = $count;
        $array['rows'] = $a;
        echo json_encode($array);
    }

    /**
     * jsonSortTree
     * 分类json树结构数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonSortTree()
    {
        $m = M('AdsSort');
        $tree = $m->field(array('id', 'ename' => 'text'))->select();
        $tree = array_merge(array(array('id' => 0, 'text' => L('全部分类'))), $tree);
        echo json_encode($tree);
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
        $m = M('Ads');
        $id = I('get.id');
        if ($id != 0) {//id为0时调用全部文档;
            $condition['a.sort_id'] = array('eq', $id);
        }
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $title = $_REQUEST['title'];
        if ($title) {
            $condition['a.name'] = array('like', '%' . $title . '%');
        }
        $count = $m->Table(C('DB_PREFIX') . 'ads a')->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->Table(C('DB_PREFIX') . 'ads a')
                        ->join(C('DB_PREFIX') . 'ads_sort s on a.sort_id=s.id')
                        ->field('s.ename,a.addtime,a.status,a.name,a.id')
                        ->where($condition)->limit($firstRow . ',' . $pageRows)->order('a.id desc')->select();
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