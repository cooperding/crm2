<?php

//要启用多语言功能，需要配置开启多语言行为，在应用的配置目录下面的行为定义文件tags.php中
return array(
// 添加下面一行定义即可
    'app_begin' => array('Behavior\CheckLangBehavior'),
);
?>
