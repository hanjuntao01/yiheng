<?php

/**
 * DSC OPEN API统一接口
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhuo $
 * $Id: api.php zhuo $
 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init_api.php');
require(dirname(__FILE__) . '/plugins/dscapi/autoload.php');

/* 初始化基础类 */
$base = new app\func\base();

$base->get_request_filter();

/* 获取传值 */
$method = isset($_REQUEST['method']) && !empty($_REQUEST['method']) ? strtolower(addslashes($_REQUEST['method'])) : ''; //接口名称
$app_key = isset($_REQUEST['app_key']) && !empty($_REQUEST['app_key']) ? $base->dsc_addslashes($_REQUEST['app_key']) : '';  //接口名称app_key
$format = isset($_REQUEST['format']) && !empty($_REQUEST['format']) ? strtolower($_REQUEST['format']) : 'json'; //传输类型

$data = isset($_REQUEST['data']) && !empty($_REQUEST['data']) ? addslashes_deep($_REQUEST['data']) : "*";  //数据
$page_size = isset($_REQUEST['page_size']) && !empty($_REQUEST['page_size']) ? intval($_REQUEST['page_size']) : 15; //默认分页当页条数
$page = isset($_REQUEST['page']) && !empty($_REQUEST['page']) ? intval($_REQUEST['page']) : 1; //默认第一页

$sort_by = isset($_REQUEST['sort_by']) ? $base->get_addslashes($_REQUEST['sort_by']) : ''; //排序字段
$sort_order = isset($_REQUEST['sort_order']) ? $base->get_addslashes($_REQUEST['sort_order']) : 'ASC'; //排序（升降）

$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('open_api') . " WHERE app_key = '$app_key' AND is_open = 1";
$open_api = $GLOBALS['db']->getRow($sql);
if ($app_key) {
    
    if (!$open_api) {
        die("暂无该接口权限");
    }else{
        $action_code = isset($open_api['action_code']) && !empty($open_api['action_code']) ? explode(",", $open_api['action_code']) : array();
        
        if(empty($action_code)){
            die("暂无该接口权限");
        }else if(!in_array($method, $action_code)){
            die("暂无该接口权限");
        }
    }
}else{
    die("密钥不能为空");
}

/* JSON或XML格式转换数组 */
if ($format == "json" && $data) {
    $data = stripslashes($data);
    $data = stripslashes($data);
    $data = json_decode($data, true);
}else{
    $data = htmlspecialchars_decode($data);
    $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

/*  
 * 相关接口
 * 
 * 商品接口 goods
 * 订单接口 order
 * 会员接口 user
 * 地区接口 region
 * 仓库地区接口 warehouse
 * 属性接口 attribute
 * 分类接口 category
 * 品牌接口 brand
 */
$interface = array('goods', 'order', 'user', 'region', 'warehouse', 'attribute', 'category', 'brand');
$interface = $base->get_interface_file(dirname(__FILE__), $interface);

foreach($interface as $key=>$row){
    require($row);
}

/* 商品 */
if (in_array($method, $goods_action)) 
{
    $file_type = "goods";
} 

/* 订单 */
elseif (in_array($method, $order_action)) 
{
    $file_type = "order";
} 

/* 订单 */
elseif (in_array($method, $user_action)) 
{
    $file_type = "user";
}

/* 地区 */
elseif (in_array($method, $region_action)) 
{
    $file_type = "region";
}

/* 仓库地区 */
elseif (in_array($method, $warehouse_action)) 
{
    $file_type = "warehouse";
}

/* 属性 */
elseif (in_array($method, $attribute_action)) 
{
    $file_type = "attribute";
}


/* 类目 */
elseif (in_array($method, $category_action)) 
{
    $file_type = "category";
}

/* 类目 */
elseif (in_array($method, $brand_action)) 
{
    $file_type = "brand";
}

else 
{
    die("非法入口");
}

require(dirname(__FILE__) . '/plugins/dscapi/view/' . $file_type . ".php");