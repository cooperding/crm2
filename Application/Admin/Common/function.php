<?php

/*
 * 公共方法
 */

/**
 * updatePath
 * 更新路径
 * @access public
 * @return boolean
 * @version dogocms 1.0
 */
function updatePath($sort_id, $sort_path, $tbname)
{
    $m = M($tbname);
    $condition['path'] = array('like', '%,' . $sort_id . ',%');
    $condition['parent_id'] = array('eq', intval($sort_id));
    $condition['_logic'] = 'OR';
    $result = $m->field('id,path')->where($condition)->select();
    if ($result) {
        foreach ($result as $k => $v) {
            $data['path'] = $sort_path . substr($v['path'], strpos($v['path'], $sort_id . ','), strlen($v['path']));
            $m->where('id=' . intval($v['id']))->save($data);
        }
    }
}

/**
 * updateRolePath
 * 更新角色节点的路径
 * @access public
 * @return boolean
 * @version dogocms 1.0
 */
function updateRolePath($sort_id, $sort_path, $tbname)
{
    $m = M($tbname);
    $condition['path'] = array('like', '%,' . $sort_id . ',%');
    $condition['pid'] = array('eq', intval($sort_id));
    $condition['_logic'] = 'OR';
    $result = $m->field('id,path')->where($condition)->select();
    foreach ($result as $k => $v) {
        $data['path'] = $sort_path . substr($v['path'], strpos($v['path'], $sort_id . ','), strlen($v['path']));
        $m->where('id=' . intval($v['id']))->save($data);
    }
}
