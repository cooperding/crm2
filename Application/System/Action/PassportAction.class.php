<?php

/**
 * PassportAction.class.php
 * 后台登录页面
 * 后台核心文件，用于后台登录操作验证
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:20
 * @package  Controller
 */
namespace System\Action;
use Think\Action;
class PassportAction extends BasecommAction {

    //初始化
    function _initialize() {
        parent::_initialize(); //继承父级
    }

    /**
     * index
     * 进入登录页面
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function index() {
        if (session('LOGIN_M_STATUS') != 'TRUE') {
            $this->redirect('User/index/index');
        }
        //此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        if (session('LOGIN_SYS_STATUS') == 'TRUE') {
            $this->redirect('./index');
        } else {
            $this->login();
        }
    }

    /*
     * login 
     * 会员登录
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function login() {
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_user = $this->tpl_user; //获取主题皮肤会员模板名称
        $this->assign('title', '会员登录');
        $this->theme($skin)->display($tpl_user . 'login');
    }

    /*
     * signup 
     * 注册会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function signup() {
        $status = R('Common/System/getCfg', array('cfg_is_signup'));
        if ($status == 2) {
            $this->error('暂时关闭注册功能，请稍后访问！');
            exit;
        }//if
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_user = $this->tpl_user; //获取主题皮肤会员模板名称
        $this->assign('title', '会员注册');
        $this->theme($skin)->display($tpl_user . 'signup');
    }

    /*
     * resetPassword 
     * 注册会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function resetPassword() {
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_user = $this->tpl_user; //获取主题皮肤会员模板名称
        $this->assign('title', '重置密码');
        $this->theme($skin)->display($tpl_user . 'resetpwd');
    }

    /**
     * checkLogin
     * 登录验证
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function checkLogin() {
        $m = M('Members');
        $pwd = trim(I('post.pwd')); //密码
        if (empty($pwd)) {
            $this->error('密码不能为空！', U('Passport/login'));
            exit;
        }
        $condition['username'] = array('eq', session('LOGIN_M_NAME'));
        $rs = $m->where($condition)->field('id,email,username,addtime,password,status')->find();
        if ($rs) {
            $uname = $rs['username'];
            $password = R('Common/System/getPwd', array($uname, $pwd));
            if ($password == $rs['password']) {//密码匹配
                if ($rs['status'] == '10') {//禁用账户，不可登录
                    $this->error('您的账户被禁止登录！', __ROOT__);
                    exit();
                } else {
                    session('LOGIN_SYS_STATUS', 'TRUE');
                    session('LOGIN_M_NAME', $rs['username']);
                    session('LOGIN_M_ID', $rs['id']);
                    session('LOGIN_M_ADDTIME', $rs['addtime']);
                    session('LOGIN_M_LOGINTIME', time());
                    $this->success('登陆成功！', __MODULE__);
                }
            } else {
                $this->error('您的输入用户名或者密码错误！', U('Passport/login'));
            }
        }
    }

    /**
     * verify
     * 生成验证码
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function verify() {
        $verify = new \Think\Verify();
        $verify->useImgBg = false; //是否使用背景图片 默认为false
        //$verify->expire =; //验证码的有效期（秒）
        //$verify->fontSize = 70; //验证码字体大小（像素） 默认为25
        $verify->useCurve = false; //是否使用混淆曲线 默认为true
        $verify->useNoise = false; //是否添加杂点 默认为true
        //$verify->imageW = 70; //验证码宽度 设置为0为自动计算
        //$verify->imageH = 25; //验证码高度 设置为0为自动计算
        $verify->length = 4; //验证码位数
        //$verify->fontttf =;//指定验证码字体 默认为随机获取
        $verify->useZh = false; //是否使用中文验证码 默认false
        //$verify->bg = array(243, 251, 254); //验证码背景颜色 rgb数组设置，例如 array(243, 251, 254)
        $verify->seKey = 'verify_user_login'; //验证码的加密密钥
        $verify->entry();
    }

// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = '') {
        $verify = new \Think\Verify();
        $verify->seKey = 'verify_user_login'; //验证码的加密密钥
        return $verify->check($code);
    }

    /**
     * checkEmail
     * 验证邮箱
     * @param string $key 加密后的key
     * @param string $uid 会员编号
     * @return boolean
     * @version dogocms 1.0
     * @todo 
     */
    public function checkEmail() {
        $key = I('get.key');
        $uid = I('get.uid');
        $m = M('Members');
        $condition['id'] = array('eq', $uid);
        $condition['email_key'] = array('eq', $key);
        $data = $m->where($condition)->find();
        if ($data) {
            if ($data['email_status'] == '20') {//验证改邮箱是否曾验证成功
                $array = array('status' => 0, 'msg' => '邮箱已验证成功！');
            } else {
                $time = (int) time() - (int) $data['email_sendtime'];
                if ($time > 60 * 60 * 24 * 2) {//两天
                    $array = array('status' => 1, 'msg' => '验证无效，验证时间超时！');
                } else {
                    $_data['email_key'] = '';
                    $_data['email_authtime'] = time();
                    $_data['email_status'] = '20';
                    $rs = $m->where($condition)->save($_data);
                    if ($rs) {
                        $array = array('status' => 0, 'msg' => '邮箱验证成功！');
                    } else {
                        $array = array('status' => 1, 'msg' => '验证失败，请重新发送验证邮件！');
                    }
                }//if
            }
        } else {
            $array = array('status' => 1, 'msg' => '验证失败，请重新发送验证邮件！');
        }
        $skin = $this->skin; //获取前台主题皮肤名称
        $tpl_user = $this->tpl_user; //获取主题皮肤会员模板名称
        $this->assign('title', '邮箱验证');
        $this->assign('data', $array);
        $this->theme($skin)->display($tpl_user . 'checkemail');
    }

}
