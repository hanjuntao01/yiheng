<?php

/**
 * DSC 商品模型
 * 抽象类
 * 不可（new）实例化
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: orderModel.php 2017-01-11 zhuo $
 */

namespace app\model;

use app\func\common;
use app\func\base;
use languages\orderLang;

abstract class orderModel extends common {

    private $alias;

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    public function __construct() {
        
    }
    
    /**
     * 查询条件
     *
     * @access  public
     * @param   string where    查询条件
     * @return  string
     */
    public function get_where($val = array(), $alias = '') {

        $where = 1;
        
        $conditions = '';
        if ($val['seller_id'] > 0 || $val['mobile'] > 0) {
            $conditions .= " AND (SELECT count(*) FROM " . $GLOBALS['ecs']->table('order_info') . " AS oi2 WHERE oi2.main_order_id = {$alias}order_id) = 0";
        }

        /* 商家ID */
        if($val['seller_id'] != -1){
            $conditions .= " AND (SELECT og.ru_id FROM " .$GLOBALS['ecs']->table('order_goods'). " AS og WHERE {$alias}order_id = og.order_id LIMIT 1)" . base::db_create_in($val['seller_id']);
            $where .= base::get_where(0, '', $conditions);
        }
        
        /* 订单ID */
        $where .= base::get_where($val['order_id'], $alias . 'order_id');
        
        /* 订单编号 */
        $where .= base::get_where($val['order_sn'], $alias . 'order_sn');
        
        /* 订单联系手机号码 */
        $where .= base::get_where($val['mobile'], $alias . 'mobile');
        
        /* 订单商品ID */
        $where .= base::get_where($val['rec_id'], $alias . 'rec_id');
        
        /* 商品货号 */
        $where .= base::get_where($val['goods_sn'], $alias . 'goods_sn');
        
        /* 商品ID */
        $where .= base::get_where($val['goods_id'], $alias . 'goods_id');
        
        return $where;
    }
    
    /**
     * 查询获取列表数据
     *
     * @access  public
     * @param   string $table    表名称
     * @param   string $select    查询字段
     * @param   string where    查询条件
     * @param   string $page_size    页码
     * @param   string $page    当前页
     * @return  string
     */
    public function get_select_list($table, $select, $where, $page_size, $page, $sort_by, $sort_order, $alias = ''){
       
        $table_alias = '';
        if(!empty($alias)){
            $table_alias = " AS " . str_replace(".", "", $alias);
            $sort_by = $alias . $sort_by;
        }
        
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table($table) .$table_alias. " WHERE " . $where;
        $result['record_count'] = $GLOBALS['db']->getOne($sql);
        
        if ($sort_by) {
            $where .= " ORDER BY $sort_by $sort_order ";
        }

        $where .= " LIMIT " . ($page - 1) * $page_size . ",$page_size";
        
        $sql = "SELECT " . $select . " FROM " . $GLOBALS['ecs']->table($table) .$table_alias. " WHERE " . $where;
        
        $result['list'] = $GLOBALS['db']->getAll($sql);

        return $result;
    }
    
    /**
     * 查询获取单条数据
     *
     * @access  public
     * @param   string $table    表名称
     * @param   string $select    查询字段
     * @param   string where    查询条件
     * @return  string
     */
    public function get_select_info($table, $select, $where, $alias = '') {
        
        $table_alias = '';
        if(!empty($alias)){
            $table_alias = " AS " . str_replace(".", "", $alias);
        }

        $sql = "SELECT " . $select . " FROM " . $GLOBALS['ecs']->table($table) .$table_alias. " WHERE " . $where . " LIMIT 1";
        $result = $GLOBALS['db']->getRow($sql);
        return $result;
    }
    
    /**
     * 插入数据
     *
     * @access  public
     * @param   string where    查询条件
     * @return  string
     */
    public function get_insert($table, $select, $format){
        
        $orderLang = orderLang::lang_order_insert();

        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, "INSERT");
        $id = $GLOBALS['db']->insert_id();

        $common_data = array(
            'result' => empty($id) ? "failure" : 'success',
            'msg' => empty($id) ? $orderLang['msg_failure']['failure'] : $orderLang['msg_success']['success'],
            'error' => empty($id) ? $orderLang['msg_failure']['error'] : $orderLang['msg_success']['error'],
            'format' => $format
        );

        common::common($common_data);
        return common::data_back();
    }
    
    /**
     * 更新数据
     *
     * @access  public
     * @param   string where    查询条件
     * @return  string
     */
    public function get_update($table, $select, $where, $format){
        
        $orderLang = orderLang::lang_order_update();

        if (strlen($where) != 1) {
            $info = $this->get_select_info($table, "*", $where);
            if (!$info) {
                $common_data = array(
                    'result' => 'failure',
                    'msg' => $orderLang['null_failure']['failure'],
                    'error' => $orderLang['null_failure']['error'],
                    'format' => $format
                );
            } else {

                $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, "UPDATE", $where);

                $common_data = array(
                    'result' => empty($select) ? "failure" : 'success',
                    'msg' => empty($select) ? $orderLang['msg_failure']['failure'] : $orderLang['msg_success']['success'],
                    'error' => empty($select) ? $orderLang['msg_failure']['error'] : $orderLang['msg_success']['error'],
                    'format' => $format
                );
            }
        } else {
            $common_data = array(
                'result' => 'failure',
                'msg' => $orderLang['where_failure']['failure'],
                'error' => $orderLang['where_failure']['error'],
                'format' => $format
            );
        }

        common::common($common_data);
        return common::data_back();
    }
    
    /**
     * 数据删除
     *
     * @access  public
     * @param   string where    查询条件
     * @return  string
     */
    public function get_delete($table, $where, $format){
        
        $orderLang = orderLang::lang_order_delete();

        if (strlen($where) != 1) {

            $sql = "DELETE FROM " . $GLOBALS['ecs']->table($table) . " WHERE " . $where;
            $GLOBALS['db']->query($sql);

            $common_data = array(
                'result' => 'success',
                'msg' => $orderLang['msg_success']['success'],
                'error' => $orderLang['msg_success']['error'],
                'format' => $format
            );
        } else {
            $common_data = array(
                'result' => 'failure',
                'msg' => $orderLang['where_failure']['failure'],
                'error' => $orderLang['where_failure']['error'],
                'format' => $format
            );
        }
        
        common::common($common_data);
        return common::data_back();
    }
    
    /**
     * 格式化返回值
     *
     * @access  public
     * @return  string
     */
    public function get_list_common_data($result, $page_size, $page, $orderLang, $format){
        $common_data = array(
            'page_size' => $page_size,
            'page' => $page,
            'result' => empty($result) ? "failure" : 'success',
            'msg' => empty($result) ? $orderLang['msg_failure']['failure'] : $orderLang['msg_success']['success'],
            'error' => empty($result) ? $orderLang['msg_failure']['error'] : $orderLang['msg_success']['error'],
            'format' => $format
        );

        common::common($common_data);
        $result = common::data_back($result, 1);
        
        return $result;
    }
    
    /**
     * 格式化返回值
     *
     * @access  public
     * @return  string
     */
    public function get_info_common_data_fs($result, $orderLang, $format) {
        $common_data = array(
            'result' => empty($result) ? "failure" : 'success',
            'msg' => empty($result) ? $orderLang['msg_failure']['failure'] : $orderLang['msg_success']['success'],
            'error' => empty($result) ? $orderLang['msg_failure']['error'] : $orderLang['msg_success']['error'],
            'format' => $format
        );

        common::common($common_data);
        $result = common::data_back($result);
        
        return $result;
    }
    
    /**
     * 格式化返回值
     *
     * @access  public
     * @return  string
     */
    public function get_info_common_data_f($orderLang, $format) {
        
        $result = array();
        
        $common_data = array(
            'result' => 'failure',
            'msg' => $orderLang['where_failure']['failure'],
            'error' => $orderLang['where_failure']['error'],
            'format' => $format
        );
        
        common::common($common_data);
        $result = common::data_back($result);
        
        return $result;
    }

}
