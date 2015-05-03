<?php

/**
 * ContentAction.class.php
 * 前台首页
 * 前台内容页面文件
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 过滤无用字段,筛选符合条件的信息（需要全站共同使用）
 */
namespace Home\Action;
use Think\Action;
class ContentAction extends BasehomeAction {

    public function index($id) {
        //接收到的是文档title id，通过该id查询取得相应的内容
        //$id = intval($id);
        $t = D('Title');
        $condition['t.id'] = array('eq', $id);
        $condition['t.status'] = array('eq', '12'); //12已审核
        $condition['t.is_recycle'] = array('eq', '10'); //10未加入回收站

        $data = $t->field(array('t.*', 'c.*'))
                ->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                ->field('t.*,c.content')
                ->where($condition)
                ->find();
        if ($data) {
            //浏览量赋值+1
            $condition_id['id'] = array('eq', $id);
            $t->where($condition_id)->setInc('views', 1);

            //获取评论信息，以后获取从其他方法返回值
            /*
            $c = D('Comment');
            $condition_comment['c.title_id'] = array('eq', $id);
            $condition_comment['c.status'] = array('eq', 20);
            $comment = $c->field(array('m.id as uid,m.username', 'c.*'))
                            ->Table(C('DB_PREFIX') . 'comment c')
                            ->join(C('DB_PREFIX') . 'members m ON m.id = c.members_id ')
                            ->where($condition_comment)->order('floor asc')->select();
             * 
             */
            $data['content'] = stripslashes($data['content']);
            $condition_sort['id'] = array('eq', $data['sort_id']);
            $tpl_content = M('NewsSort')->where($condition_sort)->getField('template_content');
        }
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_home = $this->tpl_home;//获取主题皮肤模板名称
        $this->assign('data', $data);
        //$this->assign('list_comment', $comment);
        $this->assign('title', $data['title']);
        $this->assign('keywords', $data['keywords']);
        $this->assign('description', $data['description']);
        $this->theme($skin)->display($tpl_home . $tpl_content);
    }

}
