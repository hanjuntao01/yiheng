<?php

/**
 * DSC 公共函数类
 * 可传参
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: common.php 2017-01-11 zhuo $
 */

namespace app\func;

class common {

    static private $format = 'json';
    static private $page_size = 10;         //每页条数
    static private $page = 1;               //当前页
    static private $charset = 'utf-8';      //数据类型
    static private $result;                 //返回成功与否
    static private $msg;                    //消息提示
    static private $error;                  //错误类型
    static private $allowOutputType = array(
        'xml' => 'application/xml',
        'json' => 'application/json',
        'html' => 'text/html',
    );

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    public function __construct($data = array()) {
        self::common($data);
    }

    /**
     * 构造函数
     *
     * @access  public
     * @return  bool
     */
    static public function common($data = array()) {
        /* 初始查询条件值 */
        self::$format = isset($data['format']) ? $data['format'] : 'josn';
        self::$page_size = isset($data['page_size']) ? $data['page_size'] : 10;
        self::$page = isset($data['page']) ? $data['page'] : 1;
        self::$msg = isset($data['msg']) ? $data['msg'] : '';
        self::$result = isset($data['result']) ? $data['result'] : 'success';
        self::$error = isset($data['error']) ? $data['error'] : 0;
    }

    /**
     *  返回结果集
     *
     *  @param   mixed      $info       返回的有效数据集或是错误说明
     *  @param   string     $msg        为空或是错误类型代号
     *  @param   string     $result     请求成功或是失败的标识
     *
     */
    static public function data_back($info = array(), $arr_type = 0) {
        
        /* 二维数组数据 */
        if ($arr_type == 1) {
            $list = self::page_array(self::$page_size, self::$page, $info);    //分页处理
            $info = $list;
        }
        
        $data_arr = array('result' => self::$result, 'error' => self::$error, 'msg' => self::$msg);
        
        if ($info) {
            $data_arr['info'] = $info;
        }
        
        $data_arr = self::to_utf8_iconv($data_arr);  //确保传递的编码为UTF-8
        
        /* 分为xml和json两种方式 */
        if (self::$format == 'xml') {

            /* xml方式 */
            if (isset(self::$allowOutputType[self::$format])) { //过滤content_type
                header('Content-Type: ' . self::$allowOutputType[self::$format] . '; charset=' . self::$charset);
            }

            return self::xml_encode($data_arr);
        } else {
            /* json方式 */
            if (isset(self::$allowOutputType[self::$format])) { //过滤content_type
                header('Content-Type: ' . self::$allowOutputType[self::$format] . '; charset=' . self::$charset);
            }

            return json_encode($data_arr);
        }
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    static public function xml_encode($data, $root = 'dsc', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    static public function data_to_xml($data, $item = 'item', $id = 'id') {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? self::data_to_xml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }

    /**
     * 循环转码成utf8内容
     *
     * @param string $str
     * @return string
     */
    static public function to_utf8_iconv($str) {
        if (EC_CHARSET != 'utf-8') {
            if (is_string($str)) {
                return ecs_iconv(EC_CHARSET, 'utf-8', $str);
            } elseif (is_array($str)) {
                foreach ($str as $key => $value) {
                    $str[$key] = to_utf8_iconv($value);
                }
                return $str;
            } elseif (is_object($str)) {
                foreach ($str as $key => $value) {
                    $str->$key = to_utf8_iconv($value);
                }
                return $str;
            } else {
                return $str;
            }
        }
        return $str;
    }

    /**
     * 数组分页函数 核心函数 array_slice
     * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
     * $page_size  每页多少条数据
     * $page  当前第几页
     * $array  查询出来的所有数组
     * order 0 - 不变   1- 反序
     */
    static public function page_array($page_size = 1, $page = 1, $array = array(), $order = 0) {

        $arr = array();
        $pagedata = array();
        if ($array) {
            global $countpage; #定全局变量

            $start = ($page - 1) * $page_size; #计算每次分页的开始位置

            if ($order == 1) {
                $array = array_reverse($array);
            }
            
            if(isset($array['record_count'])) {
                $totals = $array['record_count'];
                $countpage = ceil($totals / $page_size); #计算总页面数
                $pagedata = $array['list'];
            } else {
                $totals = count($array);
                $countpage = ceil($totals / $page_size); #计算总页面数
                $pagedata = array_slice($array, $start, $page_size);
            }

            $filter = array(
                'page' => $page,
                'page_size' => $page_size,
                'record_count' => $totals,
                'page_count' => $countpage
            );

            $arr = array('list' => $pagedata, 'filter' => $filter, 'page_count' => $countpage, 'record_count' => $totals);
        }
        
        //返回查询数据
        return $arr; 
    }
    
    /**
     * 过滤已存在会员索引值
     * user_name
     */
    static public function get_reference_only($table, $where = 1, $select = '', $type = 0){
        
        if(!empty($select) && is_array($select)){
            $select = implode(",", $select);
        }else{
            $select = '*';
        }
        
        $sql = "SELECT $select FROM " .$GLOBALS['ecs']->table($table). " WHERE $where";
        
        if($type == 1){
            return $GLOBALS['db']->getRow($sql);
        }else{
            return $GLOBALS['db']->getOne($sql);
        }
    }
    
    public function __callStatic($method, $arguments) {
        return call_user_func_array(array(self,$method), $arguments);
    }
}
