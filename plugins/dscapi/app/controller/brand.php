<?php

/**
 * DSC 品牌接口控制类
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: brand.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\brandModel;
use languages\brandLang;

class brand extends brandModel {

    private $table;                          //表名称
    private $alias;                          //表别名
    private $brand_select = array();          //查询字段数组
    private $select;                         //查询字段字符串组
    private $brand_id = 0;                  //品牌ID
    private $brand_name = '';               //品牌名称ID
    private $format = 'json';           //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $brandLangList;                 //语言包
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->brand($where);

        $this->wehre_val = array(
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
        );
        
        $this->where = brandModel::get_where($this->wehre_val);
        $this->select = base::get_select_field($this->brand_select);
    }

    public function brand($where = array()) {

        /* 初始查询条件值 */
        $this->brand_id = $where['brand_id'];
        $this->brand_name = $where['brand_name'];
        $this->brand_select = $where['brand_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->brandLangList = brandLang::lang_brand_request();
    }

    /**
     * 多条品牌信息
     *
     * @access  public
     * @return  array
     */
    public function get_brand_list($table) {
        
        $this->table = $table['brand'];
        $result = brandModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = brandModel::get_list_common_data($result, $this->page_size, $this->page, $this->brandLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条品牌信息
     *
     * @access  public
     * @return  array
     */
    public function get_brand_info($table) {

        $this->table = $table['brand'];
        $result = brandModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = brandModel::get_info_common_data_fs($result, $this->brandLangList, $this->format);
        } else {
            $result = brandModel::get_info_common_data_f($this->brandLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入品牌信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $brand_select     字段信息
     * @return  array
     */
    function get_brand_insert($table) {

        $this->table = $table['brand'];
        return brandModel::get_insert($this->table, $this->brand_select, $this->format);
    }

    /**
     * 更新品牌信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $brand_select     商品字段信息
     * @return  array
     */
    function get_brand_update($table) {

        $this->table = $table['brand'];
        return brandModel::get_update($this->table, $this->brand_select, $this->where, $this->format);
    }

    /**
     * 删除品牌信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_brand_delete($table) {

        $this->table = $table['brand'];
        return brandModel::get_delete($this->table, $this->where, $this->format);
    }
}
