<?php

/**
 * NewsAction.class.php
 * 信息内容
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:23
 * @package  Controller
 * @todo 信息各项操作
 */
namespace Admin\Action;
use Think\Action;
class NewsAction extends BaseAction {

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
        $id = I('get.id');
        $flag = array(
            'h' => ' 头条[h] ',
            'r' => ' 推荐[r] ',
            's' => ' 特荐[s] ',
            't' => ' 置顶[t] ',
            'p' => ' 图片[p] ',
            'j' => ' 跳转[j] '
        );
        $status = array(
            '12' => ' 审核 ',
            '10' => ' 未审核 ',
            '11' => ' 未通过审核 '
        );
        $this->assign('id', $id);
        $this->assign('flag', $flag);
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
        $m = M('Title');
        $id = I('get.id');
        $condition_id['t.id'] = array('eq', $id);
        $data = $m->field(array('t.*', 'c.content'))
                        ->Table(C('DB_PREFIX') . 'title t')
                        ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                        ->where($condition_id)->find();
        $flag = array(
            'h' => ' 头条[h] ',
            'r' => ' 推荐[r] ',
            's' => ' 特荐[s] ',
            't' => ' 置顶[t] ',
            'p' => ' 图片[p] ',
            'j' => ' 跳转[j] '
        );
        $status = array(
            '12' => ' 审核 ',
            '10' => ' 未审核 ',
            '11' => ' 未通过审核 '
        );
        $this->assign('data', $data);
        $this->assign('flag', $flag);
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->display();
    }

    /**
     * insert
     * 写入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $t = M('Title');
        $data['title'] = I('post.title');
        $data['sort_id'] = I('post.sort_id');
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择文档分类！', false, true);
        }
        //开始写入信息
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['op_id'] = session('LOGIN_UID');
        $data['status'] = $_POST['status'][0];
        $data['subtitle'] = I('post.subtitle');
        $data['flag'] = implode(',', I('post.flag'));
        
        $data['titlepic'] = I('post.titlepic');
        $data['keywords'] = I('post.keywords');
        $data['description'] = I('post.description');
        
        
        $rs = $t->add($data);
        $last_id = $t->getLastInsID();
        if ($rs == true) {
            $c = M('Content');
            $data_c['title_id'] = intval($last_id);
            $data_c['content'] = $_POST['content'];
            $rsc = $c->data($data_c)->add();
            if ($rs == true || $rsc == true) {
                $this->dmsg('2', ' 操作成功！', true);
            }
        } else {
            $this->dmsg('1', '操作失败！', false, true);
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
        $t = M('Title');
        $c = M('Content');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);//title表更新条件
        $condition_c['title_id'] = array('eq', $id);//content表更新条件
        
        $data['title'] = I('post.title');
        $data['subtitle'] = I('post.subtitle');
        $data['flag'] = implode(',', I('post.flag'));
        $data['sort_id'] = I('post.sort_id');
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择文档分类！', false, true);
        }
        $data['titlepic'] = I('post.titlepic');
        $data['keywords'] = I('post.keywords');
        $data['description'] = I('post.description');
        
        $data['updatetime'] = time();
        $data['op_id'] = session('LOGIN_UID');
        $data['status'] = $_POST['status']['0'];
        
        $data_c['content'] = $_POST['content'];
        $rs = $t->where($condition)->save($data);
        $rsc = $c->where($condition_c)->save($data_c);
        if ($rs == true || $rsc == true) {
            $this->dmsg('2', '更新成功！', true);
        } else {
            $this->dmsg('1', '更新失败', false, true);
        }
    }

    /**
     * delete
     * 删除文档到回收站
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function delete()
    {
        $t = M('Title');
        $id = I('post.id');
        $condition['id'] = array('in', $id);
        if (empty($condition['id'])) {
            $this->dmsg('1', '未有id值，操作失败！', false, true);
        }
        $rs = $t->where($condition)->setField('is_recycle', '11');
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * tempmodel
     * 写入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function tempmodel()
    {
        $mf = new ModelFieldModel();
        $id = I('post.id');
        $condition_sort['sort_id'] = array('eq', $id);
        $data_filed = $mf->where($condition_sort)->order('myorder asc,id asc')->select();
        foreach ($data_filed as $k => $v) {
            $exp = explode(',', $v['evalue']);
            if ($v['etype'] == 'radio') {
                $data_filed[$k]['opts'] = $exp;
            } elseif ($v['etype'] == 'checkbox') {
                $data_filed[$k]['opts'] = $exp;
            } elseif ($v['etype'] == 'select') {
                $data_filed[$k]['opts'] = $exp;
            }
        }
        $this->assign('id', time());
        $this->assign('filed', $data_filed);
        $this->display();
    }

    /**
     * recycle
     * 回收站信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function recycle()
    {
        $this->display();
    }

    /**
     * recycleRevert
     * 回收站还原信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function recycleRevert()
    {
        $t = M('Title');
        $id = I('post.id');
        $condition['id'] = array('in', $id);
        if (empty($condition['id'])) {
            $this->dmsg('1', '未有id值，操作失败！', false, true);
        }
        $rs = $t->where($condition)->setField('is_recycle', '10');
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * deleteRec
     * 从回收站彻底删除信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function deleteRec()
    {
        $t = M('Title');
        $c = M('Content');
        $id = I('post.id');
        $data['id'] = array('in', $id);
        $cdata['title_id'] = array('in', $id);
        $rst = $t->where($data)->delete();
        $rsc = $c->where($cdata)->delete();
        if ($rst == true) {
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
        $m = M('Title');
        $s = M('NewsSort');
        $id = I('get.id');
        if ($id != 0) {//id为0时调用全部文档
            $condition_sort['id'] = $id;
            $condition_sort['path'] = array('like', '%,' . $id . ',%');
            $condition_sort['_logic'] = 'OR';
            $data_sort = $s->field('id')->where($condition_sort)->select();
            $sort_id = '';
            foreach ($data_sort as $v) {
                $sort_id .= $v['id'] . ',';
            }
            $sort_id = rtrim($sort_id, ',');
            $condition['t.sort_id'] = array('in', $sort_id);
        }
        $title = $_REQUEST['title'];
        if ($title) {
            $condition['t.title'] = array('like', '%' . $title . '%');
        }
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);

        $condition['t.is_recycle'] = isset($_GET['is_recycle']) ? '11' : '10';
        $count = $m->table(C('DB_PREFIX') . 'title t')->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->table(C('DB_PREFIX') . 'title t')
                        ->join('left join '.C('DB_PREFIX') . 'news_sort nt on nt.id=t.sort_id')
                        ->field('t.title,t.addtime,t.status,t.id,t.views,nt.text')
                        ->where($condition)->limit($firstRow . ',' . $pageRows)->order('t.id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if ($v['status'] == '12') {
                    $data[$k]['status'] = '已审核';
                } elseif ($v['status'] == '10') {
                    $data[$k]['status'] = '未审核';
                } elseif ($v['status'] == '11') {
                    $data[$k]['status'] = '<a href="javascript:void(0)" title="驳回" style="color:#F74343;">驳回审核</a>';
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

    /**
     * jsonSortTree
     * 分类树信息json数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonSortTree()
    {
        $qiuyun = new \Org\Util\Qiuyun;
        $m = M('NewsSort');
        $tree = $m->field('id,parent_id,text')->select();
        $tree = $qiuyun->list_to_tree($tree, 'id', 'parent_id', 'children');
        $tree = array_merge(array(array('id' => 0, 'text' => '全部文档')), $tree);
        echo json_encode($tree);
    }

}
