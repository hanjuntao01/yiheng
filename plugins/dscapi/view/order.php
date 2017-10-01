<?php

/**
 * DSC 商品接口入口
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: order.php zhuo $
 */
/* 获取传值 */
$seller_id = isset($_REQUEST['seller_id']) ? $base->get_intval($_REQUEST['seller_id']) : -1;                    //商家ID
$order_id = isset($_REQUEST['order_id']) ? $base->get_intval($_REQUEST['order_id']) : -1;                       //订单ID
$order_sn = isset($_REQUEST['order_sn']) ? $base->get_addslashes($_REQUEST['order_sn']) : -1;                   //订单编号
$mobile = isset($_REQUEST['mobile']) ? $base->get_addslashes($_REQUEST['mobile']) : -1;                         //订单联系手机号码
$rec_id = isset($_REQUEST['rec_id']) ? $base->get_intval($_REQUEST['rec_id']) : -1;                             //订单商品ID
$goods_id = isset($_REQUEST['goods_id']) ? $base->get_intval($_REQUEST['goods_id']) : -1;                   //商品ID
$goods_sn = isset($_REQUEST['goods_sn']) ? $base->get_addslashes($_REQUEST['goods_sn']) : -1;                   //商品货号

$val = array(
    'seller_id' => $seller_id,
    'order_id' => $order_id,
    'order_sn' => $order_sn,
    'mobile' => $mobile,
    'rec_id' => $rec_id,
    'goods_id' => $goods_id,
    'goods_sn' => $goods_sn,
    'order_select' => $data,
    'page_size' => $page_size,
    'page' => $page,
    'sort_by' => $sort_by,
    'sort_order' => $sort_order,
    'format' => $format
);

/* 初始化商品类 */
$order = new app\controller\order($val);

switch ($method) {

    /**
     * 获取订单列表
     */
    case 'dsc.order.list.get':
        
        $table = array(
            'order' => 'order_info'
        );

        $result = $order->get_order_list($table);

        die($result);
        break;

    /**
     * 获取单条订单信息
     */
    case 'dsc.order.info.get':
        
        $table = array(
            'order' => 'order_info'
        );

        $result = $order->get_order_info($table);

        die($result);
        break;

    /**
     * 插入订单信息
     */
    case 'dsc.order.insert.post':
        
        $table = array(
            'order' => 'order_info'
        );

        $result = $order->get_order_insert($table);

        die($result);
        break;

    /**
     * 更新订单信息
     */
    case 'dsc.order.update.post':
        
        $table = array(
            'order' => 'order_info'
        );

        $result = $order->get_order_update($table);

        die($result);
        break;
    
    /**
     * 删除订单信息
     */
    case 'dsc.order.del.post':
        
        $table = array(
            'order' => 'order_info'
        );

        $result = $order->get_order_delete($table);

        die($result);
        break;
    
    /**
     * 获取订单商品列表
     */
    case 'dsc.order.goods.list.get':
        
        $table = array(
            'goods' => 'order_goods'
        );

        $result = $order->get_order_goods_list($table);

        die($result);
        break;

    /**
     * 获取单条订单商品信息
     */
    case 'dsc.order.goods.info.get':
        
        $table = array(
            'goods' => 'order_goods'
        );

        $result = $order->get_order_goods_info($table);

        die($result);
        break;

    /**
     * 插入订单商品信息
     */
    case 'dsc.order.goods.insert.post':
        
        $table = array(
            'goods' => 'order_goods'
        );

        $result = $order->get_order_goods_insert($table);

        die($result);
        break;

    /**
     * 更新订单商品信息
     */
    case 'dsc.order.goods.update.post':
        
        $table = array(
            'goods' => 'order_goods'
        );

        $result = $order->get_order_goods_update($table);

        die($result);
        break;
    
    /**
     * 删除订单商品信息
     */
    case 'dsc.order.goods.del.post':
        
        $table = array(
            'goods' => 'order_goods'
        );

        $result = $order->get_order_goods_delete($table);

        die($result);
        break;
    
    default :

        echo "非法接口连接";
        break;
}