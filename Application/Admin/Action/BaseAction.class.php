<?php

/**
 * BaseAction.class.php
 * 后台登录状态及权限认证
 * 后台核心文件，其他控制器文件将使用该文件进行登录和权限判断
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 权限验证
 */
namespace Admin\Action;
use Think\Action;
class BaseAction extends Action {

    //初始化
    function _initialize()
    {
        //检测是否登录
        if (session('LOGIN_STATUS') != 'TRUE') {
            redirect(__MODULE__ . '/Passport'); //跳转到登录网关
            exit;
        }
        $uid = session('LOGIN_UID');
        //if (!in_array($uid, C('ADMINISTRATOR'))) {//验证超级管理员不用进行权限认证
            //$auth = new \Think\Auth(); //加载Auth类库
            //$authcheck = $auth->check(MODULE_NAME . '/' . ACTION_NAME, session('LOGIN_UID'));
//        if (!$authcheck) {
//            echo '您没有此项操作权限！';
//            exit;
//        }
       // }
        $this->assign('style_common', '__PUBLIC__/Common');
        $this->assign('style', '/Skin/Admin/' . C('DEFAULT_THEME'));
    }

//endf
    /**
     * dmsg
     * json格式提示信息
     * @param string $status 状态1:失败,2:成功
     * @param string $info 提示信息
     * @param boolean $isclose 是否关闭弹出窗口true:关闭,false:不关闭
     * @param boolean $type 调试信息,true:调试信息exit,false:不调试信息
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function dmsg($status, $info, $isclose = false, $type = false)
    {
        $array = array();
        if ($isclose) {
            $array['isclose'] = 'ok';
        }
        $array['status'] = $status;
        $array['info'] = $info;
        echo json_encode($array);
        if ($type) {
            exit;
        }
    }

}

?>