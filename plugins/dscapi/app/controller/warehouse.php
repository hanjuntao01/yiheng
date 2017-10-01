<?php

/**
 * DSC 仓库地区接口控制类
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: Warehouse.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\warehouseModel;
use languages\warehouseLang;

class warehouse extends warehouseModel {

    private $table;                          //表名称
    private $alias;                          //表别名
    private $warehouse_select = array();     //查询字段数组
    private $select;                         //查询字段字符串组
    private $region_id = 0;                  //地区ID
    private $region_code = 0;                //编码CODE
    private $parent_id = 0;                  //父级ID
    private $region_name = '';               //地区名称ID
    private $region_type = 0;                //地区层级val
    private $format = 'json';                //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $warehouseLangList;              //语言包
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->warehouse($where);

        $this->wehre_val = array(
            'region_id' => $this->region_id,
            'region_code' => $this->region_code,
            'parent_id' => $this->parent_id,
            'region_type' => $this->region_type,
            'region_name' => $this->region_name,
        );
        
        $this->where = warehouseModel::get_where($this->wehre_val);
        $this->select = base::get_select_field($this->warehouse_select);
    }

    public function warehouse($where = array()) {

        /* 初始查询条件值 */
        $this->region_id = $where['region_id'];
        $this->region_code = $where['region_code'];
        $this->parent_id = $where['parent_id'];
        $this->region_type = $where['region_type'];
        $this->region_name = $where['region_name'];
        $this->warehouse_select = $where['warehouse_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->warehouseLangList = warehouseLang::lang_warehouse_request();
    }

    /**
     * 多条地区信息
     *
     * @access  public
     * @return  array
     */
    public function get_warehouse_list($table) {
        
        $this->table = $table['warehouse'];
        $result = warehouseModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = warehouseModel::get_list_common_data($result, $this->page_size, $this->page, $this->warehouseLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条地区信息
     *
     * @access  public
     * @return  array
     */
    public function get_warehouse_info($table) {

        $this->table = $table['warehouse'];
        $result = warehouseModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = warehouseModel::get_info_common_data_fs($result, $this->warehouseLangList, $this->format);
        } else {
            $result = warehouseModel::get_info_common_data_f($this->warehouseLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入地区信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $warehouse_select     字段信息
     * @return  array
     */
    function get_warehouse_insert($table) {

        $this->table = $table['warehouse'];
        return warehouseModel::get_insert($this->table, $this->warehouse_select, $this->format);
    }

    /**
     * 更新地区信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $warehouse_select     商品字段信息
     * @return  array
     */
    function get_warehouse_update($table) {

        $this->table = $table['warehouse'];
        return warehouseModel::get_update($this->table, $this->warehouse_select, $this->where, $this->format);
    }

    /**
     * 删除地区信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_warehouse_delete($table) {

        $this->table = $table['warehouse'];
        return warehouseModel::get_delete($this->table, $this->where, $this->format);
    }
}
