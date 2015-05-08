<?php

/**
 * AjaxAction.class.php
 * 前台jquery ajax请求处理页
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace System\Action;
use Think\Action;
class AjaxAction extends BasesystemAction {

    /**
     * checkLoginStatus
     * 验证登录状态
     * @param string $key_id 信息编号
     * @return boolean
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function checkLoginStatus()
    {
        $status = session('LOGIN_M_STATUS');
        if (!$status) {
            $array = array('status' => 3, 'msg' => '请登录后操作！');
            echo json_encode($array);
            exit;
        } else {
            return TRUE;
        }
    }

    

    /**
     * commemt
     * 评论操作
     * @param string $key_id 信息编号
     * @param string $content 评论内容
     * @param string $uid 会员编号
     * @return boolean
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function commemt()
    {
        $t = D('Title');
        $c = D('Comment');
        $key_id = I('post.key_id');
        $content = I('post.content');
        $this->checkLoginStatus(); //验证登录状态
        $ip = get_client_ip();
        $uid = session('LOGIN_M_ID'); //会员ID
        $data['title_id'] = $key_id;
        $data['ip'] = $ip;
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['members_id'] = $uid;
        $data['content'] = $content;
        //计算最大值
        $condition_comment['title_id'] = array('eq', $key_id);
        $condition_comment['parent_id'] = array('eq', 0);
        $floor = $c->where($condition_comment)->max('floor');
        $floor = $floor + 1;
        $data['floor'] = $floor;
        $rs = $c->add($data);
        if ($rs == true) {
            $condition['id'] = array('eq', $key_id);
            $t->where($condition)->setInc('num_comment');
            $data_num = $t->field('num_comment')->where($condition)->find();
            $num = $data_num['num_comment'];
            $array = array('status' => 0, 'msg' => '发表评论成功', 'num' => $num);
        } else {
            $array = array('status' => 1, 'msg' => '发表评论失败！');
        }
        echo json_encode($array);
    }

}
