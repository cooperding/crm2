<?php

/**
 * ListAction.class.php
 * 前台首页
 * 前台列表文件
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Home\Action;
use Think\Action;
class ListAction extends BasehomeAction {

    
    public function index()
    {
        $id = I('get.id');
        $t = D('Title');
        $ns = D('NewsSort');
        $condition_sort['path'] = array('like', '%,' . $id . ',%');
        $condition_sort['id'] = array('eq', $id);
        $condition_sort['_logic'] = 'OR';
        $sort_data = $ns->where($condition_sort)->select();
        $condition_sort_one['id'] = array('eq', $id);
        $one_data = $ns->where($condition_sort_one)->find();//本分类SEO信息
        foreach ($sort_data as $k => $v) {
            $sort_id .= $v['id'] . ',';
        }
        $sort_id = rtrim($sort_id, ', ');
        $condition['t.sort_id'] = array('in', $sort_id);
        $condition['t.status'] = array('eq', '12');
        $count = $t->Table(C('DB_PREFIX') . 'title t')
                        //->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                        ->where($condition)->count();
        $page = new \Org\Util\QiuyunPage($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('header', '条记录');
        $page->setConfig('theme', "%UP_PAGE% %FIRST% %LINK_PAGE% %DOWN_PAGE% %END% <li><span>%TOTAL_ROW% %HEADER% %NOW_PAGE%/%TOTAL_PAGE% 页</span></li>");
        $show = $page->show(); // 分页显示输出
        
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $t->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'news_sort ns ON ns.id = t.sort_id ')
                ->where($condition)
                ->field('t.*,ns.text')
                ->order('t.id desc')
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_home = $this->tpl_home;//获取主题皮肤模板名称
        $template = trim($one_data['template_list'])?trim($one_data['template_list']):'list';//获取模板名称
        $this->assign('title', $one_data['text']);
        $this->assign('keywords', $one_data['keywords']);
        $this->assign('description', $one_data['description']);
        $this->assign('list', $list);
        $this->assign('page', $show); // 赋值分页输出
        $this->theme($skin)->display($tpl_home . $template);
    }

}