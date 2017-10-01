<?php

/**
 * DSC 属性接口控制类
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: attribute.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\attributeModel;
use languages\attributeLang;

class attribute extends attributeModel {

    private $table;                          //表名称
    private $alias;                          //表别名
    private $attribute_select = array();     //查询字段数组
    private $select;                         //查询字段字符串组
    private $attribute_id = 0;               //地区ID
    private $parent_id = 0;                  //父级ID
    private $attribute_name = '';            //地区名称ID
    private $attribute_type = 0;             //地区层级val
    private $format = 'json';                //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $attributeLangList;              //语言包
    private $seller_type = 0;                //数据库商家ID查询字段类型（0 - user_id, 1 - ru_id）
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->attribute($where);

        $this->wehre_val = array(
            'seller_type' => $this->seller_type,
            'seller_id' => $this->seller_id,
            'attr_id' => $this->attr_id,
            'cat_id' => $this->cat_id,
            'attr_name' => $this->attr_name,
            'attr_type' => $this->attr_type,
        );

        $this->where = attributeModel::get_where($this->wehre_val);
        $this->select = base::get_select_field($this->attribute_select);
    }

    public function attribute($where = array()) {

        /* 初始查询条件值 */
        $this->seller_type = $where['seller_type'];
        $this->seller_id = $where['seller_id'];
        $this->attr_id = $where['attr_id'];
        $this->cat_id = $where['cat_id'];
        $this->attr_name = $where['attr_name'];
        $this->attr_type = $where['attr_type'];
        $this->attribute_select = $where['attribute_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->attributeLangList = attributeLang::lang_attribute_request();
    }
    
    /**
     * 多条属性类型信息
     *
     * @access  public
     * @return  array
     */
    public function get_goodstype_list($table) {
        
        $this->table = $table['goodstype'];
        $result = attributeModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = attributeModel::get_list_common_data($result, $this->page_size, $this->page, $this->attributeLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条属性类型信息
     *
     * @access  public
     * @return  array
     */
    public function get_goodstype_info($table) {

        $this->table = $table['goodstype'];
        $result = attributeModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = attributeModel::get_info_common_data_fs($result, $this->attributeLangList, $this->format);
        } else {
            $result = attributeModel::get_info_common_data_f($this->attributeLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入属性类型信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $attribute_select     字段信息
     * @return  array
     */
    function get_goodstype_insert($table) {

        $this->table = $table['goodstype'];
        return attributeModel::get_insert($this->table, $this->attribute_select, $this->format);
    }

    /**
     * 更新属性类型信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $attribute_select     商品字段信息
     * @return  array
     */
    function get_goodstype_update($table) {

        $this->table = $table['goodstype'];
        return attributeModel::get_update($this->table, $this->attribute_select, $this->where, $this->format);
    }

    /**
     * 删除属性类型信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goodstype_delete($table) {

        $this->table = $table['attribute'];
        return attributeModel::get_delete($this->table, $this->where, $this->format);
    }

    /**
     * 多条属性信息
     *
     * @access  public
     * @return  array
     */
    public function get_attribute_list($table) {
        
        $this->table = $table['attribute'];
        $result = attributeModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = attributeModel::get_list_common_data($result, $this->page_size, $this->page, $this->attributeLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条属性信息
     *
     * @access  public
     * @return  array
     */
    public function get_attribute_info($table) {

        $this->table = $table['attribute'];
        $result = attributeModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = attributeModel::get_info_common_data_fs($result, $this->attributeLangList, $this->format);
        } else {
            $result = attributeModel::get_info_common_data_f($this->attributeLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入属性信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $attribute_select     字段信息
     * @return  array
     */
    function get_attribute_insert($table) {

        $this->table = $table['attribute'];
        return attributeModel::get_insert($this->table, $this->attribute_select, $this->format);
    }

    /**
     * 更新属性信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $attribute_select     商品字段信息
     * @return  array
     */
    function get_attribute_update($table) {

        $this->table = $table['attribute'];
        return attributeModel::get_update($this->table, $this->attribute_select, $this->where, $this->format);
    }

    /**
     * 删除属性信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_attribute_delete($table) {

        $this->table = $table['attribute'];
        return attributeModel::get_delete($this->table, $this->where, $this->format);
    }
}
