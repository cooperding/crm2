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
namespace Home\Action;
use Think\Action;
class PassportAction extends BasehomeAction {

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

//此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        $status = session('LOGIN_M_STATUS');
        if ($status == 'TRUE') {
            $this->redirect('..' . __MODULE__);
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
        $ver_code = I('post.v_code');
        $verify_status = $this->check_verify($ver_code);
        if (!$verify_status) {
            $this->error('验证码输入错误或已过期！', U('Passport/login'));
            exit;
        }
        $email = trim(I('post.email')); //邮箱
        if (empty($email)) {
            $this->error('用户名或邮箱帐号不能为空！', U('Passport/login'));
            exit;
        }
        $pwd = trim(I('post.pwd')); //密码
        if (empty($pwd)) {
            $this->error('密码不能为空！', U('Passport/login'));
            exit;
        }
        $condition['email|username'] = array('eq', $email);
        $rs = $m->where($condition)->field('id,email,username,addtime,password,status')->find();
        if ($rs) {
            $uname = $rs['username'];
            $password = R('Common/System/getPwd', array($uname, $pwd));
            if ($password == $rs['password']) {//密码匹配
                if ($rs['status'] == '10') {//禁用账户，不可登录
                    $this->error('您的账户被禁止登录！', __ROOT__);
                    exit();
                } else {
                    session('LOGIN_M_STATUS', 'TRUE');
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
     * getNewPwd
     * 找回新的密码
     * @access public
     * @return boolean
     * @version dogocms 1.0
     * @todo 完善密码找回操作，增加邮件发送功能
     */
    public function getNewPwd() {
        $v_code = I('post.v_code');
        $verify_status = $this->check_verify($v_code);
        if (!$verify_status) {
            $this->error('验证码为空或者输入错误！');
            exit;
        }
        $email = trim(I('post.email')); //邮箱
        if (empty($email)) {
            $this->error('注册邮箱不能为空！');
            exit;
        }
        $m = M('Members');
        //先验证邮箱是否存在
        $condition['email'] = array('eq', $email);
        $rs = $m->where($condition)->field('id,username')->find();
        if (!$rs) {
            $this->error('注册邮箱不正确！');
            exit;
        }
        //随机生成密码，并发送到注册邮箱中
        $pwd = rand(100000, 999999);
        $uname = $rs['username'];
        $password = R('Common/System/getPwd', array($uname, $pwd));
        $data['password'] = $password;
        $data['updatetime'] = time();
        $condition_id['id'] = $rs['id'];
        $rs_pwd = $m->where($condition_id)->save($data);
        if ($rs_pwd == true) {
            $rs_email = R('Common/System/sendEmail', array($email, '找回密码', $pwd));
            if ($rs_email) {
                $this->success('重置密码成功，请登录邮箱查看！', __MODULE__);
            } else {
                $this->error('重置密码失败，请重新发送！');
            }
        } else {
            $this->error('重置密码失败！');
        }
    }

    /**
     * register
     * 注册会员
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function register() {
        $m = M('Members');
        $v_code = I('post.v_code');
        $verify_status = $this->check_verify($v_code);
        if (!$verify_status) {
            $this->error('验证码为空或者输入错误！');
        }
        $username = I('post.uname'); //用户名
        $email = I('post.email'); //邮箱
        $pwd = I('post.pwd'); //密码
        $pwd2 = I('post.pwd2'); //密码2
        if (empty($username)) {
            $this->error('用户名不能为空！');
        }
        if (empty($email)) {
            $this->error('邮箱不能为空！');
        }
        if (empty($pwd) || empty($pwd2)) {
            $this->error('密码不能为空！');
        }
        if ($pwd != $pwd2) {
            $this->error('两次密码输入不一致！');
        }
        $condition_uname['username'] = array('eq', $uname);
        $rs_uname = $m->where($condition_uname)->find();
        if ($rs_uname) {
            $this->error('用户名已经存在！');
        }
        $condition_email['email'] = array('eq', $email);
        $rs_email = $m->where($condition_email)->find();
        if ($rs_email) {
            $this->error('邮箱已经存在！');
        }
        $password = R('Common/System/getPwd', array($uname, $pwd));
        $data['username'] = $uname;
        $data['password'] = $password;
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['email'] = $email;
        $data['ip'] = get_client_ip();
        $rs = $m->data($data)->add();
        if ($rs == true) {
            $this->success('注册成功,请登录后操作！', __MODULE__ . '/Passport/login');
        } else {
            $this->error('注册失败，请联系管理员！');
        }
    }

    /**
     * logout
     * 退出登录，清除session
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function logout() {
        $type = I('post.type');
        session('[destroy]');
        if ($type == '10') {
            $array = array('status' => 0, 'msg' => '您已经成功退出会员系统！');
            echo json_encode($array);
            exit;
        } else {
            $this->success('您已经成功退出会员系统！', __ROOT__);
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
