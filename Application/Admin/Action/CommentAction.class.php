<?php

/**
 * CommentAction.class.php
 * 评论信息
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class CommentAction extends BaseAction {

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
     * edit
     * 编辑信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function edit()
    {
        $m = D('Comment');
        $id = I('get.id');
        $condition['c.id'] = array('eq', $id);
        $data = $m->field(array('c.*', 't.title', 'm.username'))->Table(C('DB_PREFIX') . 'comment c')
                        ->join(C('DB_PREFIX') . 'title t ON t.id=c.title_id')
                        ->join(C('DB_PREFIX') . 'members m ON m.id=c.members_id')
                        ->limit($firstRow . ',' . $pageRows)->order('c.id desc')
                        ->where($condition)->find();
        $status = array(
            '20' => ' 是 ',
            '10' => ' 否 '
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
        $m = D('Comment');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $data['updatetime'] = time();
        $data['status'] = I('post.status')['0'];
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
        $m = D('Comment');
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
     * jsonList
     * 取得列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonList()
    {
        $m = D('Comment');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->field(array('c.*', 't.title', 'm.username'))->Table(C('DB_PREFIX') . 'comment c')
                        ->join(C('DB_PREFIX') . 'title t ON t.id=c.title_id')
                        ->join(C('DB_PREFIX') . 'members m ON m.id=c.members_id')
                        ->limit($firstRow . ',' . $pageRows)->order('c.id desc')->select();
        $array = array();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                $data[$k]['replytime'] = date('Y-m-d H:i:s', $v['replytime']);
                if ($v['status'] = '20') {
                    $data[$k]['status'] = '已审核';
                } else {
                    $data[$k]['status'] = '未审核';
                }
            }
        } else {
            $data = array();
        }
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

}

?>