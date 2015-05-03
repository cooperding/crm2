<?php

/**
 * DataAction.class.php
 * 数据信息
 * @author cooper ding <qiuyuncode@163.com.com>
 * @copyright 2012- www.dingcms.com www.dogocms.com www.qiuyuncode.com www.adminsir.net All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class DataAction extends BaseAction {
    /**
     * recover
     * 站点数据还原
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function recover()
    {
        $table = $this->getTables();
        $struct = $this->bakStruct($table);
        $record = $this->bakRecord($table);
        echo '<pre>';
        print_r($record);
        exit;
        $this->display();
    }
    /**
     * backup
     * 站点数据备份--显示数据表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function backup()
    {
        $table = $this->getTables();
        //$struct = $this->bakStruct($table);
        //$record = $this->bakRecord($table);
//        echo '<pre>';
//        print_r($table);
//        exit;
        $this->assign('list',$table);
        $this->display();
    }
  /**
     * backup
     * 站点数据备份--显示数据表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function doBackup()
    {
        //print_r($_POST['tbname']);
        $tbname = $_POST;
        foreach($tbname as $k=>$v){
            $tbnae .= $k;
        }
        //print_r($tbname);
        $this->dmsg('1',$tbnae, false, true);
        exit;
    }
    /**
     * @description 获取当前数据库的所有表名。
     * @static
     * @return array
     */
    static protected function getTables()
    {
        $dbName = C('DB_NAME');
        $result = M()->query("SHOW FULL TABLES FROM `{$dbName}` WHERE Table_Type = 'BASE TABLE'");
        foreach ($result as $v) {
            $tbArray[] = $v['Tables_in_' . C('DB_NAME')];
        }
        return $tbArray;
    }

    /**
     * 备份数据表结构
     * 
     * 取得数据表结构
     */
    protected function bakStruct($array)
    {
        foreach ($array as $v) {
            $tbName = $v;
            $result = M()->query('show columns from ' . $tbName);
            $sql.="-- ----------------------------\r";
            $sql.="-- Records of  `$tbName`\r";
            $sql.="-- ----------------------------\r";
            $sql .= "DROP TABLE IF EXISTS `$tbName`;\r";
            $sql.="create table `$tbName` (\r\n";
            $rsCount = count($result);
            foreach ($result as $k => $v) {
                $field = $v['Field'];
                $type = $v['Type'];
                $default = $v['Default'];
                $extra = $v['Extra'];
                $null = $v['Null'];
                if (!($default == '')) {
                    $default = 'default \'' . $default.'\'';
                }
                if ($null == 'NO') {
                    $null = 'NOT NULL';
                } else {
                    $null = "NULL";
                }
                if ($v['Key'] == 'PRI') {
                    $key = 'PRIMARY KEY';
                } else {
                    $key = '';
                }
                if ($k < ($rsCount - 1)) {
                    $sql.="`$field` $type $null $default $key $extra,\r";
                } else {
                    //最后一条不需要","号
                    $sql.="`$field` $type $null $default $key $extra \r";
                }
            }
            $sql.=")ENGINE=MyISAM DEFAULT CHARSET=utf8\r";
        }
        return str_replace(')', ')', $sql);
    }

    /**
     * 备份数据表数据
     * 
     * 取得数据信息
     */
    protected function bakRecord($array)
    {
        foreach ($array as $v) {
            $tbName = $v;
            $rs = M()->query('select * from ' . $tbName);
            if (count($rs) <= 0) {
                continue;
            }
            $sql.="-- ----------------------------\r";
            $sql.="-- Records of `$tbName`\r";
            $sql.="-- ----------------------------\r";
            foreach ($rs as $k => $v) {
                $sql.="INSERT INTO `$tbName` VALUES (";
                foreach ($v as $key => $value) {
                    if ($value == '') {
                        $value = 'null';
                    }
                    $type = gettype($value);
                    if ($type == 'string') {
                        $value = "'" . addslashes($value) . "'";
                    }
                    $sql.=$value.',';
                }
                $sql =rtrim($sql,', ');//删除最后一个逗号
                $sql.=");\r";
            }
        }
        return str_replace(')', ')', $sql);
    }

}

?>