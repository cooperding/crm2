<?php

/**
 * PagesAction.class.php联动模型
 * 单页文档
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-2-11 20:09
 * @package  Controller
 * @todo 
 */
namespace Admin\Action;
use Think\Action;
class PagesAction extends BaseAction {

    /**
     * index
     * 单页列表页
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
        $m = M('Pages');
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
        $m = M('Pages');
        $data['ename'] = I('post.ename');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['ename'])) {
            $this->dmsg('1', '单页名不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['status'] = $_POST['status'][0];
        $data['keywords'] = I('post.keywords');
        $data['description'] = I('post.description');
        $data['content'] = I('post.content');
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
        $m = M('Pages');
        $data['ename'] = I('post.ename');
        $data['sort_id'] = I('post.sort_id');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        if (empty($data['ename'])) {
            $this->dmsg('1', '单页名不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['updatetime'] = time();
        $data['status'] = $_POST['status'][0];
        $data['keywords'] = I('post.keywords');
        $data['description'] = I('post.description');
        $data['content'] = I('post.content');
        
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * 单页删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = M('Pages');
        $id = I('post.id');
        $condition_id['id'] = array('eq', $id);
        $del = $m->where($condition_id)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sort
     * 联动分类信息
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
     * 单页分类添加
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
     * 单页分类编辑
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortedit()
    {
        $m = M('PagesSort');
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
     * 单页分类插入
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function sortinsert()
    {
        $m = M('PagesSort');
        $parent_id = I('post.parent_id');
        $ename = I('post.ename');
        if (empty($ename)) {
            $this->dmsg('1', '分类名不能为空！', false, true);
        }
        $en_name = I('post.en_name');
        if (empty($en_name)) {
            $pinyin = new \Org\Util\Pinyin();
            $data_up['en_name'] = $pinyin->output($en_name);
        }
        if ($parent_id != 0) {
            $condition_pid['id'] = array('eq', $parent_id);
            $data = $m->where($condition_pid)->find();
            $data_up['path'] = $data['path'] . $parent_id . ',';
        }
        $data_up['status'] = $_POST['status'][0];
        $data_up['updatetime'] = time();
        $data_up['parent_id'] = $parent_id;
        $data_up['ename'] = $ename;
        $data_up['myorder'] = I('post.myorder');
        $data_up['keywords'] = I('post.keywords');
        $data_up['description'] = I('post.description');
        if ($m->create($data_up)) {
            $rs = $m->add();
            if ($rs) {
                $this->dmsg('2', '操作成功！', true);
            } else {
                $this->dmsg('1', '操作失败！', false, true);
            }
        }//if
    }

    /**
     * sortupdate
     * 单页分类更新
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function sortupdate()
    {
        $m = M('PagesSort');
        $id = I('post.id');
        $parent_id = I('post.parent_id');
        $tbname = 'PagesSort';
        $ename = I('post.ename');
        if (empty($ename)) {
            $this->dmsg('1', '分类名不能为空！', false, true);
        }
        if ($parent_id != 0) {//不为0时判断是否为子分类
            if ($id == $parent_id) {
                $this->dmsg('1', '不能选择自身分类为父级分类！', false, true);
            }
            $condition_sort['id'] = array('eq', $parent_id);
            $condition_sort['path'] = array('like', '%,' . $id . ',%');
            $cun = $m->field('id')->where($condition_sort)->find(); //判断id选择是否为其的子类
            if ($cun) {
                $this->dmsg('1', '不能选择当前分类的子类为父级分类！', false, true);
            }
            //获取父级path
            $condition_pid['id'] = array('eq', $parent_id);
            $data = $m->field('path')->where($condition_pid)->find();
            $sort_path = $data['path'] . $parent_id . ','; //取得不为0时的path
            $data_up['path'] = $data['path'] . $parent_id . ',';
            updatePath($id, $sort_path, $tbname);//用于批量更新
        } else {//为0，path为,
            $condition_id['id'] = array('eq', $id);
            $data = $m->field('parent_id')->where($condition_id)->find();
            if ($data['parent_id'] != $parent_id) {//相同不改变
                $sort_path = ','; //取得不为0时的path
                updatePath($id, $sort_path, $tbname);//用于批量更新
            }
            $data_up['path'] = ','; //应该是这个
        }
        $data_up['status'] = $_POST['status']['0'];
        $data_up['updatetime'] = time();
        $data_up['parent_id'] = $parent_id;
        $data_up['ename'] = $ename;
        $data_up['myorder'] = I('post.myorder');
        $data_up['keywords'] = I('post.keywords');
        $data_up['description'] = I('post.description');
        
        $en_name = I('post.en_name');
        if (empty($en_name)) {
            import("ORG.Util.Pinyin");
            $pinyin = new \Org\Util\Pinyin();
            $data_up['en_name'] = $pinyin->output($en_name);
        }
        $condition['id'] = array('eq',$id);
        $rs = $m->where($condition)->save($data_up);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '未有操作或操作失败！', false, true);
        }
    }

    /**
     * sortdelete
     * 单页分类删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortdelete()
    {
        $m = M('PagesSort');
        $id = I('post.id');
        $condition_path['path'] = array('like', '%,' . $id . ',%');
        $data = $m->field('id')->where($condition_path)->select();
        if (is_array($data)) {
            $this->dmsg('1', '该分类下还有子级分类，无法删除！', false, true);
        }
        $t = M('Pages');
        $condition_sort['sort_id'] = array('eq', $id);
        $t_data = $t->field('sort_id')->where($condition_sort)->find();
        if (is_array($t_data)) {
            $this->dmsg('1', '该分类下还有文档信息，无法删除！', false, true);
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
     * jsonTree
     * 分类json树结构数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonSortList()
    {
        $m = M('PagesSort');
        $list = $m->field(array('id', 'parent_id', 'ename' => 'text','status'))->select();
        $navcatCount = $m->count("id");
        $a = array();
        $array = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $a[$k] = $v;
                $a[$k]['_parentId'] = intval($v['parent_id']); //_parentId为easyui中标识父id
                if($v['status']==20){
                    $a[$k]['status'] = '启用';
                }else{
                    $a[$k]['status'] = '禁用';
                }
            }
        } else {
            $array['rows'] = 0;
        }
        $array['total'] = $navcatCount;
        $array['rows'] = $a;
        echo json_encode($array);
    }

    /**
     * jsonTree
     * 分类json树结构数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree()
    {
        $qiuyun = new \Org\Util\Qiuyun();
        $m = M('PagesSort');
        $tree = $m->field(array('id', 'parent_id', 'ename' => 'text'))->select();
        $tree = $qiuyun->list_to_tree($tree, 'id', 'parent_id', 'children');
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
        $m = M('Pages');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $title = $_REQUEST['title'];
        if ($title) {
            $condition['ename'] = array('like', '%' . $title . '%');
        }
        $count = $m->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->where($condition)->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if($v['status']==20){
                    $data[$k]['status'] = '启用';
                }else{
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

