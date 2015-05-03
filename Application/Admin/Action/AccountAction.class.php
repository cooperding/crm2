<?php

/**
 * AccountAction.class.php
 * 后台个人资料及密码操作
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:20
 * @package  Controller
 */
namespace Admin\Action;
use Think\Action;
class AccountAction extends BaseAction
{

    /**
     * index
     * 进入登录页面
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function index()
    {

    }
     /**
     * perinfo
     * 个人资料
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function perinfo()
    {
        $m = D('Operators');
        $uid = session('LOGIN_UID');
        $condition['id'] = array('eq',$uid);
        $data = $m->where($condition)->find();
        
        $this->assign('data', $data);
        $this->display();
    }
     /**
     * changepwd
     * 修改密码
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function changepwd()
    {
        $this->display();
    }
     /**
     * updatepwd
     * 更新密码
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function updatepwd()
    {
        $m = D('Operators');
        $oldpwd = I('post.oldpwd');
        $newpwd = I('post.newpwd');
        $newpwd2 = I('post.newpwd2');
        if(empty($oldpwd)||empty($newpwd)||empty($newpwd2)){
            $this->dmsg('1', '密码不能为空！', false, true);
        }
        if($newpwd!=$newpwd2){
            $this->dmsg('1', '新密码输入不相同，请确认输入！', false, true);
        }
        $uid = session('LOGIN_UID');
        $username = session('LOGIN_NAME');
        $condition['id'] = array('eq',$uid);
        $rs = $m->field('creat_time,password')->where($condition)->find();
        $password = R('Common/System/getPwd', array($username, $oldpwd));
        if($password!=$rs['password']){
            $this->dmsg('1', '原密码输入不正确，请确认输入！', false, true);
        }
        $data['password'] = R('Common/System/getPwd', array($username, $newpwd));
        $rs_s = $m->where($condition)->save($data);
        if ($rs_s == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '未有操作或操作失败！', false, true);
        }
        
    }
    /**
     * updateInfo
     * 更新个人资料
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function updateInfo()
    {
        $this->dmsg('1', '暂无该功能！', false, true);
    }

}