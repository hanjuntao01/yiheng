<?php

/**
 * DSC 商品接口控制类
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: Order.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\orderModel;
use languages\orderLang;

class order extends orderModel {

    private $table;                          //表名称
    private $alias = '';                     //表别名
    private $order_select = array();         //查询字段数组
    private $select;                         //查询字段字符串组
    private $seller_id = 0;                  //商家ID
    private $order_id = 0;                   //订单ID
    private $order_sn = 0;                   //订单编号
    private $mobile = 0;                     //订单联系手机号码
    private $goods_sn = '';                  //商品货号
    private $goods_id = 0;                   //商品ID
    private $rec_id = 0;                     //订单商品ID
    private $format = 'json';                //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $goodsLangList;                  //语言包
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->order($where);

        $this->wehre_val = array(
            'seller_id' => $this->seller_id,
            'order_id' => $this->order_id,
            'order_sn' => $this->order_sn,
            'mobile' => $this->mobile,
            'rec_id' => $this->rec_id,
            'goods_sn' => $this->goods_sn,
            'goods_id' => $this->goods_id,
        );
        
        if($this->seller_id > 0 || $this->mobile > 0){
            $this->alias = 'o.';
        }
        
        $this->where = orderModel::get_where($this->wehre_val, $this->alias);
        $this->select = base::get_select_field($this->order_select);
    }

    public function order($where = array()) {

        /* 初始查询条件值 */
        $this->seller_id = $where['seller_id'];
        $this->order_id = $where['order_id'];
        $this->order_sn = $where['order_sn'];
        $this->mobile = $where['mobile'];
        $this->rec_id = $where['rec_id'];
        $this->goods_sn = $where['goods_sn'];
        $this->goods_id = $where['goods_id'];
        $this->order_select = $where['order_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->goodsLangList = orderLang::lang_order_request();
    }

    /**
     * 多条订单信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_order_list($table) {
        
        $this->table = $table['order'];
        $result = orderModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order, $this->alias);
        $result = orderModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条订单信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_order_info($table) {

        $this->table = $table['order'];
        $result = orderModel::get_select_info($this->table, $this->select, $this->where, $this->alias);
        
        if (strlen($this->where) != 1) {
            $result = orderModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = orderModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入订单信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $order_select     商品字段信息
     * @return  array
     */
    function get_order_insert($table) {

        $this->table = $table['order'];
        return orderModel::get_insert($this->table, $this->order_select, $this->format);
    }

    /**
     * 更新订单信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $order_select     商品字段信息
     * @return  array
     */
    function get_order_update($table) {
        
        $this->table = $table['order'];
        return orderModel::get_update($this->table, $this->order_select, $this->where, $this->format);
    }

    /**
     * 删除订单信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_order_delete($table) {

        $this->table = $table['order'];
        return orderModel::get_delete($this->table, $this->where, $this->format);
    }

    /**
     * 多条订单商品信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_order_goods_list($table) {
        
        $this->table = $table['goods'];
        $result = orderModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = orderModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条订单商品信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_order_goods_info($table) {

        $this->table = $table['goods'];
        $result = orderModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = orderModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = orderModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入订单商品信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $order_select     商品字段信息
     * @return  array
     */
    function get_order_goods_insert($table) {

        $this->table = $table['goods'];
        return orderModel::get_insert($this->table, $this->order_select, $this->format);
    }

    /**
     * 更新订单商品信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $order_select     商品字段信息
     * @return  array
     */
    function get_order_goods_update($table) {
        
        $this->table = $table['goods'];
        return orderModel::get_update($this->table, $this->order_select, $this->where, $this->format);
    }

    /**
     * 删除订单商品信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_order_goods_delete($table) {

        $this->table = $table['goods'];
        return orderModel::get_delete($this->table, $this->where, $this->format);
    }
}
