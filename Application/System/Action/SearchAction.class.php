<?php

/**
 * SearchAction.class.php
 * 前台搜索文件
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-01-31 13:30
 * @package  Controller
 * @todo 完善更多方法
 */

namespace System\Action;

use Think\Action;

class SearchAction extends BasesystemAction {

    public function index() {
        //分析：根据提供的关键词查询title或者扩展内容表（暂定）,同时需要查询的表有分类表。
        $words = I('get.words');
        /*
          if (!$words) {
          echo '错误'; //跳转到错误页面
          exit;
          }
         * 
         */

        $t = M('Title');
        $ns = M('NewsSort');
        $condition['t.title'] = array('like', '%' . $words . '%');
        $condition['t.keywords'] = array('like', '%' . $words . '%');
        $condition['_logic'] = 'OR';
        $count = $t->Table(C('DB_PREFIX') . 'title t')->where($condition)->count();
        $page = new \Org\Util\QiuyunPage($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $t
                ->field(array('t.id,t.sort_id,t.title,t.subtitle,t.titlepic,t.views,t.keywords,t.description,t.addtime,t.updatetime,t.num_top,t.num_beat,t.num_comment,ns.text'))
                ->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'news_sort ns ON ns.id = t.sort_id')
                ->where($condition)->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_home = $this->tpl_home;//获取主题皮肤模板名称
        $this->assign('list', $list); // 赋值数据集
        $this->assign('words', $words);
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('title', $one_data['text']);
        $this->assign('keywords', $one_data['keywords']);
        $this->assign('description', $one_data['description']);
        $this->theme($skin)->display($tpl_home . 'search');
    }

}
