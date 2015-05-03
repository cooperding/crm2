<?php

/**
 * YounuoEndecry
 * 加密解密类
 * @author cooper <qiuyuncode@163.com>
 * @copyright 版本归原作者所有，感谢辛勤付出
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2014-11-23 21:15
 * @package  Controller
 * @todo 
 */

namespace Org\Younuo;

class YounuoEndecry {

    private $enkey; //加密解密用的密钥
    private $rep_char = '#'; //替换加密后的base64字符串中的=,因为=在有些场合是禁止使用的，

    //这里可以用一个允许的字符作为替换。
    //构造参数是密钥

    public function __construct($key = '') {
        if (!$key) {
            $this->enkey = $key;
        }
    }

    //设置密钥
    public function set_key($key) {
        $this->enkey = $key;
    }

    private function keyED($txt, $encrypt_key) {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key)) {
                $ctr = 0;
            }
            $tmp.= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }
        return $tmp;
    }

    //加密字符串
    public function encrypt($txt, $key = '') {
        if (!$key) {
            $key = $this->enkey;
        }
        srand((double) microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key)) {
                $ctr = 0;
            }
            $tmp.= substr($encrypt_key, $ctr, 1) .
                    (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
            $ctr++;
        }
        $r = base64_encode($this->keyED($tmp, $key));
        $r = str_replace('=', $this->rep_char, $r);
        return $r;
    }

    //解密字符串
    public function decrypt($txt, $key = '') {
        $txt = str_replace($this->rep_char, '=', $txt);
        $txt = base64_decode($txt);
        if (!$key) {
            $key = $this->enkey;
        }
        $txt = $this->keyED($txt, $key);
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = substr($txt, $i, 1);
            $i++;
            $tmp.= (substr($txt, $i, 1) ^ $md5);
        }
        return $tmp;
    }

}
