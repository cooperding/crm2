<?php

/**
 * BaseApiAction.class.php
 * Api公共方法
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Common\Action;
use Think\Action;
class BaseApiAction extends Action {
    /*
     * _initialize
     * 
     * @todo 判断获取数据的权限
     */
    function _initialize()
    {
        
    }

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

    /**
     * getPassword
     * 密码生成格式-获得密码
     * @param string $uname 用户名
     * @param string $pwd 密码明文
     * @access public
     * @return string
     * @version dogocms 1.0
     */
    public function getPassword($uname, $pwd)
    {
        $password = md5(sha1(md5($uname) . sha1($pwd)));
        return $password;
    }

}

?>
