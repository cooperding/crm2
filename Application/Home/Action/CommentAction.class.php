<?php

/**
 * CommentAction.class.php
 * 评论相关操作文件
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Home\Action;
use Think\Action;
class CommentAction extends BasehomeAction {

    public function index()
    {
        /*
          $m = M('Setting');
          $title['sys_name'] = array('eq', 'cfg_title');
          $keywords['sys_name'] = array('eq', 'cfg_keywords');
          $description['sys_name'] = array('eq', 'cfg_description');
          $data_title = $m->where($title)->find();
          $data_keywords = $m->where($keywords)->find();
          $data_description = $m->where($description)->find();

          $this->assign('title', $data_title['sys_value']);
          $this->assign('keywords', $data_keywords['sys_value']);
          $this->assign('description', $data_description['sys_value']);
          $this->display(':index');
         * $this->theme($skin)->display(':index');
         *
         */
    }

    /**
     * insert
     * 写入数据
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function insert()
    {
        $content = trim($_POST['content']);
        $yzm = trim($_POST['yzm']);
        if (session('verifycomment') != md5($yzm)) {
            $array = array('status' => 1, 'msg' => '验证码输入错误，或者输入为空');
            echo json_encode($array);
            exit;
        }
        if (empty($content)) {
            $array = array('status' => 1, 'msg' => '请输入评论内容！');
            echo json_encode($array);
            exit;
        }
        $m = D('Comment');
        $data['title_id'] = intval($_POST['title_id']);
        $data['msgcontent'] = $content;
        $data['addtime'] = time();
        $data['post_id'] = session('M_UID');
        $data['ip'] = get_client_ip();
        $rs = $m->add($data);
        if ($rs == true) {
            $html = array();
            $array = array('status' => 2, 'msg' => $html);
            echo json_encode($array);
            exit;
        } else {
            $array = array('status' => 1, 'msg' => '添加失败');
            echo json_encode($array);
            exit;
        }
    }

    /**
     * verify
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
        $verify->seKey = 'verifycomment'; //验证码的加密密钥
        $verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        $verify->seKey = 'verifycomment'; //验证码的加密密钥
        return $verify->check($code, $id);
    }
    

}
