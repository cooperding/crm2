<?php

/**
 * BasehomeAction.class.php
 * 前台页面公共方法
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */

namespace Home\Action;

use Think\Action;

class BasehomeAction extends Action {

    //初始化
    function _initialize() {
        $skin = $this->getSkin('cfg_skin_web'); //获取前台主题皮肤名称
        $this->skin = $skin;
        $tpl_home = $this->tpl_home = C('TPL_HOME');
        $tpl_user = $this->tpl_user = C('TPL_USER');
        $navhead = R('Common/System/getNav', array('header')); //导航菜单
        $navfoot = R('Common/System/getNav', array('footer')); //导航菜单
        $this->assign('navhead', $navhead);
        $this->assign('navfoot', $navfoot);
        $this->assign('style_common', '/Common');
        $this->assign('style', '/Theme' . __MODULE__ . '/' . $skin . '/style');
        $this->assign('tpl_header', './Theme' . __MODULE__ . '/' . $skin . '/' . $tpl_home . '/header.html');
        $this->assign('tpl_footer', './Theme' . __MODULE__ . '/' . $skin . '/' . $tpl_home . '/footer.html');
        $this->assign('tpl_sidebar', './Theme' . __MODULE__ . '/' . $skin . '/' . $tpl_user . '/sidebar.html');
    }

    /*
     * getSkin
     * 获取站点设置的主题名称
     * @todo 使用程序读取主题皮肤名称
     */

    public function getSkin($str) {
        $str = $str ? $str : 'cfg_skin_web';
        $skin = R('Common/System/getCfg', array($str));
        if (!$skin) {
            $skin = C('DEFAULT_THEME');
        }
        return $skin;
    }

}

?>
