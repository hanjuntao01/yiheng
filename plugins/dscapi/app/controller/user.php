<?php

/**
 * DSC 会员接口控制类
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: User.php 2017-01-11 zhuo $
 */

namespace app\controller;

use app\func\common;
use app\func\base;
use app\model\userModel;
use languages\userLang;

class user extends userModel {

    private $table;                          //表名称
    private $alias;                          //表别名
    private $user_select = array();          //查询字段数组
    private $select;                         //查询字段字符串组
    private $user_id = 0;                    //会员ID
    private $user_name = 0;                  //会员名称
    private $mobile = '';                    //手机号
    private $rank_id = '';                   //等级ID
    private $address_id = '';                //收货地址ID
    private $format = 'json';                //返回格式（json, xml, array）
    private $page_size = 10;                 //每页条数
    private $page = 1;                       //当前页
    private $wehre_val;                      //查询条件
    private $userLangList;                   //语言包
    private $sort_by;                        //排序字段
    private $sort_order;                     //排序升降

    public function __construct($where = array()) {
        $this->user($where);

        $this->wehre_val = array(
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'mobile' => $this->mobile,
            'rank_id' => $this->rank_id,
            'address_id' => $this->address_id,
        );
        
        $this->where = userModel::get_where($this->wehre_val);
        $this->select = base::get_select_field($this->user_select);
    }

    public function user($where = array()) {

        /* 初始查询条件值 */
        $this->user_id = $where['user_id'];
        $this->user_name = $where['user_name'];
        $this->mobile = $where['mobile'];
        $this->rank_id = $where['rank_id'];
        $this->address_id = $where['address_id'];
        $this->user_select = $where['user_select'];
        $this->format = $where['format'];
        $this->page_size = $where['page_size'];
        $this->page = $where['page'];
        $this->sort_by = $where['sort_by'];
        $this->sort_order = $where['sort_order'];
        
        $this->userLangList = userLang::lang_user_request();
    }

    /**
     * 多条会员信息
     *
     * @access  public
     * @param   integer $user_id    会员ID
     * @return  array
     */
    public function get_user_list($table) {
        
        $this->table = $table['users'];
        $result = userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条会员信息
     *
     * @access  public
     * @param   integer $user_id     会员ID
     * @return  array
     */
    public function get_user_info($table) {

        $this->table = $table['users'];
        $result = userModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
        } else {
            $result = userModel::get_info_common_data_f($this->userLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入会员信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     字段信息
     * @return  array
     */
    function get_user_insert($table) {

        $this->table = $table['users'];
        return userModel::get_insert($this->table, $this->user_select, $this->format);
    }

    /**
     * 更新会员信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     商品字段信息
     * @return  array
     */
    function get_user_update($table) {

        $this->table = $table['users'];
        $data = $this->user_select;

        $where = '';
        $is_not = 0;
        if ($this->user_id != -1) {

            if (!empty($data['user_name']) && !empty($data['mobile'])) {
                $where = "(user_name = '" . $data['user_name'] . "' OR mobile_phone = '" . $data['mobile'] . "') AND user_id <> " . $this->user_id;
            } elseif (!empty($data['user_name'])) {
                $where = "user_name = '" . $data['user_name'] . "' AND user_id <> " . $this->user_id;
            } elseif (!empty($data['mobile'])) {
                $where = "mobile_phone = '" . $data['mobile'] . "' AND user_id <> " . $this->user_id;
            }
        } else {

            if (!empty($data['user_name']) && !empty($data['mobile'])) {
                $where = "user_name = '" . $data['user_name'] . "' OR mobile_phone = '" . $data['mobile'] . "'";
            } elseif (!empty($data['user_name'])) {
                $where = "user_name = '" . $data['user_name'] . "'";
            } elseif (!empty($data['mobile'])) {
                $where = "mobile_phone = '" . $data['mobile'] . "'";
            }
        }

        if (!empty($where)) {
            $user_id = common::get_reference_only($this->table, $where, array("user_id"));
            if ($user_id) {
                $is_not = 1;
            }

            if ($is_not) {
                $userLang = userLang::lang_user_update();
                $common_data = array(
                    'result' => 'failure',
                    'msg' => $userLang['is_user_name']['failure'],
                    'error' => $userLang['is_user_name']['error'],
                    'format' => $this->format
                );

                common::common($common_data);
                return common::data_back();
            }
        }

        return userModel::get_update($this->table, $this->user_select, $this->where, $this->format);
    }

    /**
     * 删除会员信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_user_delete($table) {

        $this->table = $table['users'];
        return userModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 多条会员等级信息
     *
     * @access  public
     * @param   integer $rank_id    等级ID
     * @return  array
     */
    public function get_user_rank_list($table) {
        
        $this->table = $table['rank'];
        $result = userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条会员等级信息
     *
     * @access  public
     * @param   integer $rank_id     等级ID
     * @return  array
     */
    public function get_user_rank_info($table) {

        $this->table = $table['rank'];
        $result = userModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
        } else {
            $result = userModel::get_info_common_data_f($this->userLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入会员等级信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     字段信息
     * @return  array
     */
    function get_user_rank_insert($table) {

        $this->table = $table['rank'];
        return userModel::get_insert($this->table, $this->user_select, $this->format);
    }

    /**
     * 更新会员等级信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     字段信息
     * @return  array
     */
    function get_user_rank_update($table) {

        $this->table = $table['rank'];
        return userModel::get_update($this->table, $this->user_select, $this->where, $this->format);
    }

    /**
     * 删除会员等级信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_user_rank_delete($table) {

        $this->table = $table['address'];
        return userModel::get_delete($this->table, $this->where, $this->format);
    }
    
    /**
     * 多条会员收货地址信息
     *
     * @access  public
     * @param   integer $address_id    收货地址ID
     * @return  array
     */
    public function get_user_address_list($table) {
        
        $this->table = $table['address'];
        $result = userModel::get_select_list($this->table, $this->select, $this->where, $this->page_size, $this->page, $this->sort_by, $this->sort_order);
        $result = userModel::get_list_common_data($result, $this->page_size, $this->page, $this->userLangList, $this->format);
        
        return $result;
    }

    /**
     * 单条会员收货地址信息
     *
     * @access  public
     * @param   integer $address_id     收货地址ID
     * @return  array
     */
    public function get_user_address_info($table) {

        $this->table = $table['rank'];
        $result = userModel::get_select_info($this->table, $this->select, $this->where);
        
        if (strlen($this->where) != 1) {
            $result = userModel::get_info_common_data_fs($result, $this->userLangList, $this->format);
        } else {
            $result = userModel::get_info_common_data_f($this->userLangList, $this->format);
        }
        
        return $result;
    }

    /**
     * 插入会员收货地址信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     字段信息
     * @return  array
     */
    function get_user_address_insert($table) {

        $this->table = $table['address'];
        return userModel::get_insert($this->table, $this->user_select, $this->format);
    }

    /**
     * 更新会员收货地址信息
     *
     * @access  public
     * @param   integer $table     表名称
     * @param   integer $user_select     字段信息
     * @return  array
     */
    function get_user_address_update($table) {

        $this->table = $table['address'];
        return userModel::get_update($this->table, $this->user_select, $this->where, $this->format);
    }

    /**
     * 删除会员收货地址信息
     *
     * @access  public
     * @param   string where 查询条件
     * @return  array
     */
    function get_user_address_delete($table) {

        $this->table = $table['address'];
        return userModel::get_delete($this->table, $this->where, $this->format);
    }
}
