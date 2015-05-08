<?php

//D('User','Service') //实例化UserService

namespace Home\Widget;

use Think\Controller;

class CateWidget extends Controller {

    public function menu()
    {
        $menu = M('Cate')->getField('id,title');
        $this->assign('menu', $menu);
        $this->display('Cate:menu');
    }

}
