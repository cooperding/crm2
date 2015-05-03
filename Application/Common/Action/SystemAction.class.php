<?php

/**
 * SystemAction.class.php
 * 接口文件--用于本站内信息的统一调用
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:20
 * @package  Controller
 */
namespace Common\Action;
use Think\Action;
class SystemAction extends BaseApiAction {

    /**
     * sendEmail
     * 发送邮件-邮箱接口
     * @param string $email 接收电子邮件地址
     * @param string $subject 邮件主题
     * @param string $body 邮件信息
     * @return boolean
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function sendEmail($email, $subject, $body)
    {
        // 导入Vendor类库包 Library/Vendor/PHPMailer/PHPmail.class.php
        //导入方式 import('Vendor.Zend.Server');
        import('Vendor.PHPMailer.PHPmail');
        $mail = new \PHPmail();
        $host = $this->getCfg('cfg_email_host'); //发送邮件服务器
        $username = $this->getCfg('cfg_email_username'); //账户名
        $password = $this->getCfg('cfg_email_password'); //密码
        $from = $this->getCfg('cfg_email_from'); //发送电子邮件地址
        $fromname = $this->getCfg('cfg_email_fromname'); //发送邮件名称
        $option = array(
            'host' => $host, //发送邮件服务器
            'username' => $username, //账户名
            'password' => $password, //密码
            'from' => $from, //发送电子邮件地址
            'fromname' => $fromname, //发送邮件名称
            'reply' => $from, //回复电子邮件地址
        );
        $mail->set_smtp_config($option);
        $rs = $mail->send_mail($email, $subject, $body);
        if ($rs == true) {//发送成功返回真
            return true;
        } else {//发送失败返回假
            return false;
        }
    }

    /**
     * getPwd
     * 生成密码
     * @param string $uname 用户名
     * @param string $pwd 密码明文
     * @return string
     * @version dogocms 1.0
     * @todo 禁止随意修改
     */
    public function getPwd($uname, $pwd)
    {
        return $this->getPassword($uname, $pwd);
    }

    /*
     * getCfg
     * 获取站点配置
     * @param string $name 参数名称
     * @return string
     * @$skin = R('Common/System/getCfg', array($str));
     * @version dogocms 1.0
     */

    public function getCfg($name)
    {
        $m = M('Setting');
        if ($name) {
            $condition['sys_name'] = array('eq', $name);
            $rs = $m->field('sys_value')->where($condition)->find();
            if ($rs) {
                return $rs['sys_value'];
            }
        } else {
            return false;
        }
    }

    /*
     * getNav
     * 获取导航菜单
     * @param string $type header 是头部导航菜单
     * @param string $type footer 是底部导航菜单
     */

    public function getNav($type = 'header')
    {
        if ($type == 'header') {
            $m = D('NavHead');
        } elseif ($type == 'footer') {
            $m = D('NavFoot');
        }
        $condition['status'] = array('eq', 20);
        $list = $m->where($condition)->select();
        $qiuyun = new \Org\Util\Qiuyun;
        $tree = $qiuyun->list_to_tree($list, 'id', 'parent_id', 'children');
        return $tree;
    }

    /*
     * guid
     * 生成uuid
     */

    function guid()
    {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
        return $uuid;
    }

    /**
     * uploadImg
     * 上传图片
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function uploadImg()
    {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $upload->savePath = '/Images/'; // 设置附件上传目录
        $upload->autoSub = true;
        $upload->subName = array('date', 'Ymd');
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $msg = $upload->getError();
            return array('error' => 1, 'message' => $msg);
            exit;
        } else {// 上传成功 获取上传文件信息
            $url = '/Uploads'.$info['imgFile']['savepath'] . $info['imgFile']['savename'];
            $url = __ROOT__ . '/' . $url;
            return array('error' => 0, 'url' => $url);
        }
    }

}
