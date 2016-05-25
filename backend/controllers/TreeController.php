<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/27
 * Time: 18:04
 */

namespace backend\controllers;

/**
 * 获取数组树形结构
 * @author zhaiyu
 * @date 2015-07-28
 * @modified 2015-08-07 BUG代号:736 修改了初始的nbsp的值,添加多个空格 by zhaiyu
 * @package PediaManager\Controller
 */
class TreeController {


    private $arr;//待处理数组
    private $icon;//添加符号
    private $ret = array();//返回数组
    private $nbsp = '&nbsp;&nbsp;';

    public function __construct($data, $icon)
    {
        $this->arr = $data;
        if($icon)
            $this->icon = $icon;
    }

    /**
     * 把待处理数组的id,name相关索引换成统一的id,name
     * @author zhaiyu
     * @date 2015-07-28
     * @param array $array 待处理数组
     * @param string $id 待更换的id索引
     * @param string $name 待更换的name索引
     * @param int $type 更换方式 0:更换,1:还原
     * @return array $result 更改后的数组
     */
    public function change_key($array, $id, $name, $type = 0){
        $result = array();
        if($type == 0){
            foreach($array as $value){
                $tmpArr = array();
                $tmpArr['id']= $value[$id];
                $tmpArr['name'] = $value[$name];
                unset($value[$id]);//去除原数组中的id元素
                unset($value[$name]);//去除原数组中的name元素
                $result[] = array_merge($tmpArr, $value);//合并装入新数组
            }
        }else{
            foreach($array as $value){
                $tmpArr = array();
                $tmpArr[$id]= $value['id'];
                $tmpArr[$name] = $value['name'];
                unset($value['id']);//去除数组中的id元素
                unset($value['name']);//去除数组中的name元素
                $result[] = array_merge($tmpArr, $value);//合并装入新数组
            }
        }
        return $result ? $result : false;
    }

    /**
     * 获取子节点元素
     * @author zhaiyu
     * @date 2015-07-27
     * @modified 2015-07-28 增加了传入参数,加入了数组的处理语句
     * @param string $id 需要更换的数组的id索引
     * @param string $name 需要更换的数组的name索引
     * @param int $pid 父级元素ID
     * @return array $children 子节点元素
     */
    public function get_child($id, $name, $pid){
        $children = array();
        $newArr = $this->change_key($this->arr, $id, $name);//更改数组索引
        foreach($newArr as $node){
            if($node['parent'] == $pid){
                $children[] = $node;//子元素数据填充
            }
        }
        return $children ? $children : false;
    }

    /**
     * 获取树结构
     * @author zhaiyu
     * @date 2015-07-27
     * @modified 2015-07-28 增加了传入参数、添加数组name内容的处理
     * @param string $id 需要更换的数组的id索引
     * @param string $name 需要更换的数组的name索引
     * @param int $pid 父级元素ID
     * @param string $add 控制名称的空格处理
     * @return array
     */
    public function get_tree($id, $name, $pid = 0, $add = ''){
        $children = $this->get_child($id, $name, $pid);
        if($children){
            foreach($children as $child){
                if($this->icon){
                    $space = $add ? $add : '';//空格字符串
                    $child['name'] = $space . $child['name'];
                    $this->ret[] = $child;
                    $this->get_tree($id, $name, $child['id'], $add.$this->icon);
                }else{
                    $this->ret[] = $child;
                    $this->get_tree($id, $name, $child['id']);
                }
            }
        }
        return $this->change_key($this->ret, $id, $name, 1);
    }
}