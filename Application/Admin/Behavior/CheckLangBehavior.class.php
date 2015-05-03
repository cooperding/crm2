<?php

/**
 * Description of CheckLangBehavior
 *
 * @author dingjicai
 */
namespace Admin\Behavior;
use Think\Behavior;
class CheckLangBehavior extends Behavior {

    // 行为参数定义
    protected $options = array(
    );

    // 行为扩展的执行入口必须是run
    public function run(&$param)
    {
        return;
    }

}
