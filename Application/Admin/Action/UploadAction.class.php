<?php

/**
 * UploadAction.class.php
 * 上传文件中心
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:23
 * @package  Controller
 * @todo 上传权限及各种安全过滤
 */
namespace Admin\Action;
use Think\Action;
class UploadAction extends BaseAction {

    /**
     * upload
     * 上传文件
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function uploadImg()
    {

        $array = R('Common/System/uploadImg');
        echo json_encode($array);
    }

}

?>