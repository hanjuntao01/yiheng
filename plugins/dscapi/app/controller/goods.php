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
 * $Id: Goods.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\goodsModel;
use languages\goodsLang;

class goods extends goodsModel {

    private $table;                          //表名称
    private $alias;                          //表别名
    private $goods_select = array();         //查询字段数组
    private $select;                         //查询字段字符串组
    private $seller_id = 0;                  //商家ID
    private $brand_id = 0;                   //品牌ID
    private $cat_id = 0;                     //商品分类ID
    private $user_cat = 0;                   //商品商品分类ID
    private $goods_id = 0;                   //商品ID
    private $goods_sn = '';                  //商品货号
    private $bar_code = '';                  //商品条形码
    private $w_id = 0;                       //商品仓库ID
    private $a_id = 0;                       //商品地区ID
    private $region_id = 0;                  //仓库地区ID
    private $region_sn = '';                 //商品仓库\地区货号
    private $img_id = 0;                     //商品相册ID
    private $attr_id = 0;                    //属性类型
    private $goods_attr_id = 0;              //商品属性ID
    private $tid = '';                       //商品运费模板ID
    private $seller_type = 0;                //数据库商家ID查询字段类型（0 - user_id, 1 - ru_id）
    private $format = 'json';                //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $goodsLangList;                  //语言包
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->goods($where);

        $this->wehre_val = array(
            'seller_id' => $this->seller_id,
            'brand_id' => $this->brand_id,
            'cat_id' => $this->cat_id,
            'user_cat' => $this->user_cat,
            'goods_id' => $this->goods_id,
            'goods_sn' => $this->goods_sn,
            'bar_code' => $this->bar_code,
            'w_id' => $this->w_id,
            'a_id' => $this->a_id,
            'region_id' => $this->region_id,
            'region_sn' => $this->region_sn,
            'img_id' => $this->img_id,
            'attr_id' => $this->attr_id,
            'goods_attr_id' => $this->goods_attr_id,
            'tid' => $this->tid,
            'seller_type' => $this->seller_type,
        );
        
        $this->where = goodsModel::get_where($this->wehre_val);
        $this->select = base::get_select_field($this->goods_select);
    }

    public function goods($where = array()) {

        /* 初始查询条件值 */
        $this->seller_type = $where['seller_type'];
        $this->table = $where['table'];
        $this->seller_id = $where['seller_id'];
        $this->brand_id = $where['brand_id'];
        $this->cat_id = $where['cat_id'];
        $this->user_cat = $where['user_cat'];
        $this->goods_id = $where['goods_id'];
        $this->goods_sn = $where['goods_sn'];
        $this->bar_code = $where['bar_code'];
        $this->w_id = $where['w_id'];
        $this->a_id = $where['a_id'];
        $this->region_id = $where['region_id'];
        $this->region_sn = $where['region_sn'];
        $this->img_id = $where['img_id'];
        $this->attr_id = $where['attr_id'];
        $this->goods_attr_id = $where['goods_attr_id'];
        $this->tid = $where['tid'];
        $this->goods_select = $where['goods_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->goodsLangList = goodsLang::lang_goods_request();
    }

    /**
     * 多条商品信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_goods_list($table) {
        
        $this->table = $table['goods'];
        $result = goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条商品信息
     *
     * @access  public
     * @param   integer $goods_id     商品ID
     * @return  array
     */
    public function get_goods_info($table) {

        $this->table = $table['goods'];
        $result = goodsModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $goods_select     商品字段信息
     * @return  array
     */
    function get_goods_insert($table) {

        $this->table = $table['goods'];
        return goodsModel::get_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $goods_select     商品字段信息
     * @return  array
     */
    function get_goods_update($table) {
        
        $this->table = $table['goods'];
        return goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format);
    }

    /**
     * 删除商品信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_delete($table) {

        $this->table = $table['goods'];
        return goodsModel::get_delete($this->table, $this->where, $this->format);
    }

    /**
     * 获取商品仓库列表
     * 仓库模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_warehouse_list($table) {

        $this->table = $table['warehouse'];
        $result = goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 获取单条商品仓库信息
     * 仓库模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_warehouse_info($table) {

        $this->table = $table['warehouse'];
        $result = goodsModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品仓库信息
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_warehouse_insert($table) {

        $this->table = $table['warehouse'];
        return goodsModel::get_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品仓库信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $goods_select     商品字段信息
     * @return  array
     */
    function get_goods_warehouse_update($table) {

        $this->table = $table['warehouse'];
        return goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format);
    }
    
    /**
     * 删除商品仓库信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_warehouse_delete($table) {

        $this->table = $table['warehouse'];
        return goodsModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 获取商品仓库地区列表
     * 地区模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_area_list($table) {

        $this->table = $table['area'];
        $result = goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 获取单条商品仓库地区信息
     * 地区模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_area_info($table) {
        
        $this->table = $table['area'];
        $result = goodsModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品仓库地区信息
     * 地区模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_area_insert($table) {

        $this->table = $table['area'];
        return goodsModel::get_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品仓库地区信息
     * 地区模式
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_area_update($table) {

        $this->table = $table['area'];
        return goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format);
    }
    
    /**
     * 删除商品仓库地区信息
     * 地区模式
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_area_delete($table) {
        
        $this->table = $table['area'];
        return goodsModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 获取商品相册列表
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_gallery_list($table) {

        $this->table = $table['gallery'];
        $result = goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 获取单条商品相册
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_gallery_info($table) {
        
        $this->table = $table['gallery'];
        $result = goodsModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品相册
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_gallery_insert($table) {

        $this->table = $table['gallery'];
        return goodsModel::get_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品相册
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_gallery_update($table) {

        $this->table = $table['gallery'];
        return goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format);
    }
    
    /**
     * 删除商品相册
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_gallery_delete($table) {
        
        $this->table = $table['gallery'];
        return goodsModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 获取商品属性列表
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_attr_list($table) {

        $this->table = $table['attr'];
        $result = goodsModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 获取单条商品属性
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_attr_info($table) {
        
        $this->table = $table['attr'];
        $result = goodsModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品属性
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_attr_insert($table) {

        $this->table = $table['attr'];
        return goodsModel::get_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品属性
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_attr_update($table) {

        $this->table = $table['attr'];
        return goodsModel::get_update($this->table, $this->goods_select, $this->where, $this->format);
    }
    
    /**
     * 删除商品属性
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_attr_delete($table) {
        
        $this->table = $table['attr'];
        return goodsModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 获取商品运费模板列表
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_freight_list($table) {
        
        if($this->seller_id != -1){
            $this->where = "gt.ru_id = " . $this->seller_id . " GROUP BY gt.tid";
        }
        
        $join_on = array(
            '',
            "tid|tid",
            "tid|tid"
        );
        
        $this->table = $table;
        $result = goodsModel::get_join_select_list($this->table, $this->select, $this->where, $join_on);
        $result = goodsModel::get_list_common_data($result, $this->page_size, $this->page, $this->goodsLangList, $this->format);
        
        return $result;
    }

    /**
     * 获取单条商品运费模板
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    public function get_goods_freight_info($table) {
        
        if($this->tid != -1){
            $this->where = "gt.tid = " . $this->tid . " GROUP BY gt.tid";
        }
        
        $join_on = array(
            '',
            "tid|tid",
            "tid|tid"
        );
        
        $this->table = $table;
        $result = goodsModel::get_join_select_info($this->table, $this->select, $this->where, $join_on);
        
        if (strlen($this->where) != 1) {
            $result = goodsModel::get_info_common_data_fs($result, $this->goodsLangList, $this->format);
        } else {
            $result = goodsModel::get_info_common_data_f($this->goodsLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入商品运费模板
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_freight_insert($table) {

        $this->table = $table;
        return goodsModel::get_more_insert($this->table, $this->goods_select, $this->format);
    }

    /**
     * 更新商品运费模板
     *
     * @access  public
     * @param   integer $data     商品字段信息
     * @return  array
     */
    function get_goods_freight_update($table) {

        $this->table = $table;
        return goodsModel::get_more_update($this->table, $this->goods_select, $this->where, $this->format);
    }
    
    /**
     * 删除商品运费模板
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_goods_freight_delete($table) {
        
        $this->table = $table;
        return goodsModel::get_more_delete($this->table, $this->where, $this->format);
    }
}
