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
namespace Admin\Action;
use Think\Action;
class PassportAction extends Action {

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
        
        //此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        if (session('LOGIN_STATUS') == 'TRUE') {
            $this->redirect('./index');
        } else {
            $this->assign('style', '/Skin/Admin/' . C('DEFAULT_THEME') . $skin);
            $this->display();
        }
    }

    /**
     * dologin
     * 登录验证
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function dologin()
    {
        $ver_code = I('post.vd_code');
        $verify_status = $this->check_verify($ver_code);
        if (!$verify_status) {
            $this->error('验证码输入错误或已过期！', U('Passport/index'));
            exit;
        }
        $user_name = I('post.user_name');
        $user_name = trim($user_name);//过滤空格
        $condition['username'] = array('eq', $user_name);
        $password = I('post.user_password');
        $password = trim($password);//过滤空格
        if (!empty($user_name) && !empty($password)) {//依据用户名查询
            $login = D('Operators');
            $rs = $login->field('username,creat_time,id,password')->where($condition)->find();
            if ($rs) {//对查询出的结果进行判断
                $password = R('Common/System/getPwd', array($user_name, $password));
                if ($password == $rs['password']) {//判断密码是否匹配
                    if ($rs['status'] == '10') {
                        $this->error('您的帐号禁止登录！', U('Passport/index'));
                        exit;
                    }
                    session('LOGIN_STATUS', 'TRUE');
                    session('LOGIN_NAME', $rs['username']);
                    session('LOGIN_UID', $rs['id']);
                    session('LOGIN_CTIME', $rs['creat_time']);
                    $this->success('登陆成功！', './index');
                } else {
                    $this->error('您的输入密码错误！', U('Passport/index'));
                }
            } else {
                $this->error('您的输入用户名或者密码错误！', U('Passport/index'));
            }
        } else {
            $this->error('用户名或密码输入为空！', U('Passport/index'));
        }
    }

    /**
     * logout
     * 退出登录，清除session
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function logout()
    {
        session('[destroy]');
        $this->success('您已经成功退出管理系统！', __MODULE__ . '/index');
    }

    /**
     * vercode
     * 生成验证码
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function verify()
    {
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
        $verify->seKey = 'verify_login'; //验证码的加密密钥
        $verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        $verify->seKey = 'verify_login'; //验证码的加密密钥
        return $verify->check($code, $id);
    }

}
