<?php

/**
 * ECSHOP 管理中心服务站管理
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: wanglei $
 * $Id: suppliers_server.php 15013 2009-05-13 09:31:42Z wanglei $
 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');

define('SUPPLIERS_ACTION_LIST', 'delivery_view,back_view');

//ecmoban模板堂 --zhuo start
$adminru = get_admin_ru_id();
if ($adminru['ru_id'] == 0) {
    $smarty->assign('priv_ru', 1);
} else {
    $smarty->assign('priv_ru', 0);
}
//ecmoban模板堂 --zhuo end

/* ------------------------------------------------------ */
//-- 服务站列表
/* ------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') {
    /* 检查权限 */
    admin_priv('merchants_commission');

    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => '03_merchants_commission'));

    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['brokerage_amount_list']); // 当前导航
    $smarty->assign('action_link3', array('href' => 'javascript:download_list();', 'text' => $_LANG['export_all_suppliers']));

    $store_list = get_common_store_list();
    $smarty->assign('store_list', $store_list);

    $result = merchants_commission_list();

    $smarty->assign('full_page', 1); // 翻页参数
    $smarty->assign('merchants_commission_list', $result['result']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');

    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }

    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_commission_list.dwt');
}

/* ------------------------------------------------------ */
//-- 排序、分页、查询
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'query') {
    check_authz_json('merchants_commission');

    /* 获得该管理员的权限 */
    $priv_str = $db->getOne("SELECT action_list FROM " . $ecs->table('admin_user') . " WHERE user_id = " . $_SESSION['admin_id']);

    /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
    if ($priv_str != 'all') {
        $smarty->assign('no_all', 0);
        $ser_name = $_LANG['suppliers_list_server'];
    } else {
        $smarty->assign('no_all', 1);
    }

    $store_list = get_common_store_list();
    $smarty->assign('store_list', $store_list);

    /* 查询 */
    $result = merchants_commission_list();

    $smarty->assign('merchants_commission_list', $result['result']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);

    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }

    /* 排序标记 */
    $sort_flag = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('merchants_commission_list.dwt'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/* ------------------------------------------------------ */
//-- 列表页编辑名称
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'edit_suppliers_ser_name') {
    check_authz_json('merchants_commission');

    $id = intval($_POST['id']);
    $name = json_str_iconv(trim($_POST['val']));

    /* 判断名称是否重复 */
    $sql = "SELECT suppliers_ser_id
            FROM " . $ecs->table('merchants_server') . "
            WHERE suppliers_name = '$name'
            AND suppliers_ser_id <> '$id' ";
    if ($db->getOne($sql)) {
        make_json_error(sprintf($_LANG['suppliers_name_exist'], $name));
    } else {
        /* 保存服务站信息 */
        $sql = "UPDATE " . $ecs->table('merchants_server') . "
                SET suppliers_name = '$name'
                WHERE suppliers_ser_id = '$id'";
        if ($result = $db->query($sql)) {
            /* 记日志 */
            admin_log($name, 'edit', 'suppliers_ser');

            clear_cache_files();

            make_json_result(stripslashes($name));
        } else {
            make_json_result(sprintf($_LANG['agency_edit_fail'], $name));
        }
    }
}

/* ------------------------------------------------------ */
//-- 删除服务站
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'remove') {
    check_authz_json('merchants_commission');

    $id = intval($_REQUEST['id']);

    $sql = "DELETE FROM " . $ecs->table('merchants_server') . "
            WHERE server_id = '$id'";
    $db->query($sql);

    /* 清除缓存 */
    clear_cache_files();

    $url = 'merchants_commission.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");

    exit;
}

/* ------------------------------------------------------ */
//-- 批量操作
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'batch') {
    $nowTime = gmtime();
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes'])) {
        sys_msg($_LANG['no_record_selected']);
    } else {
        /* 检查权限 */
        admin_priv('merchants_commission');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['remove'])) {
            $sql = "SELECT *
                    FROM " . $ecs->table('merchants_server') . "
                    WHERE suppliers_ser_id " . db_create_in($ids);
            $suppliers = $db->getAll($sql);

            foreach ($suppliers as $key => $value) {
                /* 判断服务站是否存在订单 */
                $sql = "SELECT COUNT(*)
                        FROM " . $ecs->table('order_info') . "AS O, " . $ecs->table('order_goods') . " AS OG, " . $ecs->table('goods') . " AS G
                        WHERE O.order_id = OG.order_id
                        AND OG.goods_id = G.goods_id
                        AND G.suppliers_ser_id = '" . $value['suppliers_ser_id'] . "'";
                $order_exists = $db->getOne($sql, TRUE);
                if ($order_exists > 0) {
                    unset($suppliers[$key]);
                }

                /* 判断服务站是否存在商品 */
                $sql = "SELECT COUNT(*)
                        FROM " . $ecs->table('goods') . "AS G
                        WHERE G.suppliers_ser_id = '" . $value['suppliers_ser_id'] . "'";
                $goods_exists = $db->getOne($sql, TRUE);
                if ($goods_exists > 0) {
                    unset($suppliers[$key]);
                }
            }
            if (empty($suppliers)) {
                sys_msg($_LANG['batch_drop_no']);
            }


            $sql = "DELETE FROM " . $ecs->table('merchants_server') . "
                WHERE suppliers_ser_id " . db_create_in($ids);
            $db->query($sql);

            /* 更新管理员、发货单关联、退货单关联和订单关联的服务站 */
            $table_array = array('admin_user', 'delivery_order', 'back_order');
            foreach ($table_array as $value) {
                $sql = "DELETE FROM " . $ecs->table($value) . " WHERE suppliers_ser_id " . db_create_in($ids) . " ";
                $db->query($sql, 'SILENT');
            }

            /* 记日志 */
            $suppliers_names = '';
            foreach ($suppliers as $value) {
                $suppliers_names .= $value['suppliers_name'] . '|';
            }
            admin_log($suppliers_names, 'remove', 'suppliers_ser');

            /* 清除缓存 */
            clear_cache_files();

            sys_msg($_LANG['batch_drop_ok']);
        }
        if ($_POST['type'] == 'button_remove') {// begin liu
            sys_msg($_LANG['is not supported']);
        } elseif ($_POST['type'] == 'button_closed') {
            $ids = $_POST['checkboxes'];
            $result = merchants_order_list_checked($ids);
            $settlement = intval(1);
            if (empty($result)) {
                sys_msg($_LANG['no_order']);
            } else {
                /* 操作日志  by kong */
                foreach ($ids as $k => $v) {
                    if (!empty($v)) {
                        /* 插入操作日志  by kong   start */
                        $db->query(" INSERT INTO" . $ecs->table("gift_gard_log") . " (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES ('" . $_SESSION['admin_id'] . "','$v','$settlement','$nowTime','toggle_on_settlement')");
                        /* by kong end */

                        $order_goods = get_order_seller_id($v);
                        $amount = get_seller_settlement_amount($v, $order_goods['ru_id']);

                        $other['admin_id'] = $_SESSION['admin_id'];
                        $other['ru_id'] = $order_goods['ru_id'];
                        $other['order_id'] = $v;
                        $other['amount'] = $amount; //结算金额
                        $other['add_time'] = $nowTime;
                        $other['log_type'] = 2;
                        $other['is_paid'] = 1;

                        $db->autoExecute($ecs->table('seller_account_log'), $other, 'INSERT');

                        $sql = "UPDATE " . $ecs->table('seller_shopinfo') . " SET seller_money = seller_money + $amount WHERE ru_id = '" . $order_goods['ru_id'] . "'";
                        $db->query($sql);
                    }
                }

                $sql = " UPDATE " . $ecs->table('order_info') . " SET is_settlement = '$settlement' WHERE order_id " . db_create_in($ids);
                $query = $db->query($sql);

                if ($query) {
                    /* 清除缓存 */
                    clear_cache_files();
                    /* 提示信息 */
                    sys_msg($_LANG['batch_closed_success']);
                }
            }
        } else {
            if (empty($_POST['type'])) {
                sys_msg($_LANG['choose_batch']);
            }
        }                     //end liu
    }
}

/* ------------------------------------------------------ */
//-- 添加/编辑佣金设置
/* ------------------------------------------------------ */ 
elseif (in_array($_REQUEST['act'], array('add', 'edit'))) {
    /* 检查权限 */
    admin_priv('merchants_commission');

    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'suppliers_list_server'));
    $smarty->assign('action_link', array('href' => 'merchants_commission.php?act=list', 'text' => $_LANG['suppliers_list_server']));
    $smarty->assign('ur_here', $_LANG['suppliers_list_server']); // 当前导航
    
    $suppliers = array();

    $id = isset($_REQUEST['id']) && !empty($_REQUEST['id']) ?  intval($_REQUEST['id']) : 0;
    
    if(empty($id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }

    $sql = "SELECT * FROM " . $ecs->table('merchants_server') . " WHERE user_id = '$id'";
    $server = $db->getRow($sql);
    
    if($server['bill_time']){
        $server['bill_time'] = local_date("Y-m-d", $server['bill_time']);
    }
    
    $sql = "SELECT * FROM " . $ecs->table('merchants_percent') . " WHERE 1 ORDER BY sort_order";
    $percent_list = $db->getAll($sql);
    
    if($server){
        $smarty->assign('form_action', 'update');
    }else{
        $smarty->assign('form_action', 'insert');
    }

    $smarty->assign('user_id', $id);
    $smarty->assign('server', $server);
    $smarty->assign('percent_list', $percent_list);
    $smarty->assign('nowtime', local_date("Y-m-d", gmtime()));
    
    $shop_name = get_shop_name($id, 1);
    $smarty->assign('shop_name', $shop_name);

    assign_query_info();
    $smarty->display('merchants_commission_info.dwt');
}

/* ------------------------------------------------------ */
//-- 插入/更新佣金
/* ------------------------------------------------------ */ 
elseif (in_array($_REQUEST['act'], array('insert', 'update'))) 
{
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $user_id = isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $commission_model = isset($_REQUEST['commission_model']) && !empty($_REQUEST['commission_model']) ? intval($_POST['commission_model']) : 0;
    $settlement_cycle = isset($_REQUEST['settlement_cycle']) && !empty($_REQUEST['settlement_cycle']) ? intval($_POST['settlement_cycle']) : 0;
    
    $day_number = !empty($_POST['day_number']) ? intval($_POST['day_number']) : 0;
    $bill_time = !empty($_POST['bill_time']) ? local_strtotime(addslashes(trim($_POST['bill_time']))) : '';
    $bill_freeze_day = !empty($_POST['bill_freeze_day']) ? intval($_POST['bill_freeze_day']) : 0;
    
    if($settlement_cycle != 7){
        $bill_time = '';
    }
     
    /* 提交值 */
    $server = array(
        'commission_model' => $commission_model,
        'cycle' => $settlement_cycle,
        'bill_freeze_day' => $bill_freeze_day,
        'suppliers_percent' => intval($_POST['suppliers_percent']),
        'day_number' => $day_number,
        'bill_time' => $bill_time,
        'suppliers_desc' => addslashes(trim($_POST['suppliers_desc']))
    );
    
    if ($_REQUEST['act'] == 'insert') {
        $server['user_id'] = $user_id;
        $db->autoExecute($ecs->table('merchants_server'), $server, 'INSERT');
    }else{
        $db->autoExecute($ecs->table('merchants_server'), $server, 'UPDATE', "user_id = '$user_id'");
    }
    
    //删除账单商家缓存文件
    dsc_unlink(ROOT_PATH .'data/sc_file/seller_list.php');
    get_cache_seller_list();
    
    /* 提示信息 */
    $links[] = array('href' => 'merchants_commission.php?act=edit&id=' . $user_id , 'text' => $_LANG['back_suppliers_server_list']);
    sys_msg($_LANG['suppliers_server_ok'], 0, $links);
}

/* ------------------------------------------------------ */
//-- 商家订单别表
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'order_list') {

    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'brokerage_order_list'));
    
    $user_id = isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    
    if (empty($user_id)) {
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }

    $server = get_seller_commission_info($user_id);
    $percent_value = $server['percent_value'] . '%';
    
    $smarty->assign('percent_value', $percent_value);

    if ($adminru['ru_id'] > 0) {
        $smarty->assign('no_all', 0);
    } else {
        $smarty->assign('no_all', 1);
    }

    /* 模板赋值 */
    $smarty->assign('action_link', array('href' => 'javascript:order_downloadList();', 'text' => $_LANG['export_merchant_commission'])); //liu
    $smarty->assign('ur_here', $_LANG['brokerage_order_list']); // 当前导航
    $smarty->assign('full_page', 1); // 翻页参数
    
    $commission_model = $server['commission_model'];
    $smarty->assign('commission_model', $commission_model); // 翻页参数

    $order_list = merchants_order_list();
    $smarty->assign('user_id', $user_id); //liu  供批量结算用
    $smarty->assign('order_list', $order_list['orders']);
    $smarty->assign('filter', $order_list['filter']);
    $smarty->assign('record_count', $order_list['record_count']);
    $smarty->assign('page_count', $order_list['page_count']);
    $smarty->assign('server_id', '<img src="images/sort_desc.gif">');

    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }

    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_order_list.dwt');
}

/* ------------------------------------------------------ */
//-- 排序、分页、查询
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'order_query') {

    check_authz_json('merchants_commission');
    
    $smarty->assign('action_link', array('href' => 'javascript:order_downloadList();', 'text' => $_LANG['export_merchant_commission'])); //liu
    
    /* 获得该管理员的权限 */
    $priv_str = $db->getOne("SELECT action_list FROM " . $ecs->table('admin_user') . " WHERE user_id = " . $_SESSION['admin_id']);

    /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
    if ($priv_str != 'all') {
        $smarty->assign('no_all', 0);
        $ser_name = $_LANG['suppliers_list_server'];
    } else {
        $smarty->assign('no_all', 1);
    }

    $order_list = merchants_order_list();
    $smarty->assign('order_list', $order_list['orders']);
    $smarty->assign('filter', $order_list['filter']);
    $smarty->assign('record_count', $order_list['record_count']);
    $smarty->assign('page_count', $order_list['page_count']);

    /* 排序标记 */
    $sort_flag = sort_flag($order_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }

    make_json_result($smarty->fetch('merchants_order_list.dwt'), '', array('filter' => $order_list['filter'], 'page_count' => $order_list['page_count']));
}

/* ------------------------------------------------------ */
//-- 修改结算状态
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'toggle_on_settlement') {
    check_authz_json('merchants_commission');

    $order_id = intval($_POST['id']);
    $on_sale = intval($_POST['val']);

    /* 检查是否重复 */
    $sql = "SELECT is_settlement,is_frozen FROM " . $ecs->table('order_info') . " WHERE order_id = '$order_id'";
    $order_exc = $db->getRow($sql);
    if ($order_exc['is_frozen']) {
        make_json_error("该订单已被冻结，不能进行此操作！");
    }
    if ($order_exc['is_settlement']) {
        make_json_error($_LANG['not_settlement']);
    }

    $nowTime = gmtime();

    $order_goods = get_order_seller_id($order_id);
    $amount = get_seller_settlement_amount($order_id, $order_goods['ru_id']);

    $other['admin_id'] = $_SESSION['admin_id'];
    $other['ru_id'] = $order_goods['ru_id'];
    $other['order_id'] = $order_id;
    $other['amount'] = $amount; //结算金额
    $other['add_time'] = $nowTime; //结算时间
    $other['log_type'] = 2;
    $other['is_paid'] = 1;
    
    $sql = "UPDATE " . $ecs->table('order_info') . " SET is_settlement = '$on_sale' WHERE order_id = '$order_id'";
    $db->query($sql);

    $db->autoExecute($ecs->table('seller_account_log'), $other, 'INSERT');

    $sql = "UPDATE " . $ecs->table('seller_shopinfo') . " SET seller_money = seller_money + $amount WHERE ru_id = '" . $order_goods['ru_id'] . "'";
    $db->query($sql);
    $change_desc = sprintf($_LANG['01_admin_settlement'], $_SESSION['admin_name']);
    $user_account_log = array(
        'user_id' => $order_goods['ru_id'],
        'user_money' => $amount,
        'change_time' => gmtime(),
        'change_desc' => $change_desc,
        'change_type' => 2
    );
    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_account_log'), $user_account_log, 'INSERT');

    /* 插入操作日志  by kong   start */
    $db->query(" INSERT INTO" . $ecs->table("gift_gard_log") . " (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES ('" . $_SESSION['admin_id'] . "','$order_id','$on_sale','$nowTime','toggle_on_settlement')");
    /* by kong end */
    clear_cache_files();
    make_json_result($on_sale);
}
/* ------------------------------------------------------ */
//-- 修改结算状态
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'toggle_on_frozen') {
    check_authz_json('merchants_commission');

    $order_id = intval($_POST['id']);
    $on_sale = intval($_POST['val']);

    $nowTime = gmtime();

    $sql = "UPDATE " . $ecs->table('order_info') . " SET is_frozen = '$on_sale' WHERE order_id = '$order_id'";
    $db->query($sql);
    if ($on_sale == 1) {
        $type = 3;
    } else {
        $type = 2;
    }
    /* 插入操作日志  by kong   start */
    $db->query(" INSERT INTO" . $ecs->table("gift_gard_log") . " (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES ('" . $_SESSION['admin_id'] . "','$order_id','$type','$nowTime','toggle_on_settlement')");
    /* by kong end */
    clear_cache_files();
    make_json_result($on_sale);
}
/* ------------------------------------------------------ */
//-- 查询商家店铺
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'query_merchants_info') {
    check_authz_json('merchants_commission');

    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);

    $date = array('shoprz_brandName, shopNameSuffix');
    $user = get_table_date('merchants_shop_information', "user_id = '$user_id'", $date);
    $user['user_id'] = $user_id;

    clear_cache_files();
    make_json_result($user);
}
/* ------------------------------------------------------ */
//--Excel文件下载 所有商家
/* ------------------------------------------------------ */
if ($_REQUEST['act'] == 'commission_download') {
    $filename = date('YmdHis') . ".csv";
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=" . $filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');

    $commission_list = merchants_commission_list();

    echo commission_download_list($commission_list['result']);
    exit;
}

/* ------------------------------------------------------ */
//--Excel文件下载 商家佣金明细
/* ------------------------------------------------------ */
elseif($_REQUEST['act'] == 'ajax_download'){
    require(ROOT_PATH . '/includes/cls_json.php');
    $json = new JSON;
    $result = array('is_stop' => 0);
    
    $page = !empty($_REQUEST['page_down'])  ?  intval($_REQUEST['page_down']) : 0;//处理的页数
    $page_count = !empty($_REQUEST['page_count'])  ?  intval($_REQUEST['page_count']) : 0;//总页数
    $merchants_order_list = merchants_order_list($page);//获取订单数组

    $_SESSION['merchants_download_content'][] = $merchants_order_list;
    $result['page'] = $page;
    if($page < $page_count){
        $result['is_stop'] = 1;//未结算标识
        $result['next_page'] = $page+1;
    }
   die($json->encode($result));
}
/* ------------------------------------------------------ */
//--Excel文件下载 商家佣金明细
/* ------------------------------------------------------ */
if ($_REQUEST['act'] == 'merchant_download') {
    $id = !empty($_REQUEST['id'])  ?  intval($_REQUEST['id']) : 0;
    $shop_name = get_shop_name($id, 1);
    header("Content-Disposition: attachment; filename=$shop_name-".date('YmdHis').".zip");
    header("Content-Type: application/unknown");
    // 获取所有商家的下载数据 按照商家分组
    include_once('includes/cls_phpzip.php');
    $merchants_download_content = $_SESSION['merchants_download_content'];
    $zip = new PHPZip;
    if(!empty($merchants_download_content)){
        foreach($merchants_download_content as $k=>$merchants_order_list){
            $k++;
            $content =  order_download_list($merchants_order_list['orders']);
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', '佣金总金额：');
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['all_price']) . "\t\n";
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', '已结算：');
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['is_settlement_price']) . "\t\n";
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', '未结算：');
            $content .= ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['no_settlement_price']) . "\t\n";
            $zip->add_file($content,  date('YmdHis').'-'.$k.'.csv');
        }
    }
    unset($_SESSION['merchants_download_content']);//清空导出对象
    die($zip->file());
}
/* ------------------------------------------------------ */
//--商家订单列表  操作日志 by kong 
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'handle_log') {
    /* 权限判断 */
    admin_priv('merchants_commission');
    $smarty->assign('ur_here', $_LANG['handle_log']);
    $user_id = !empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
    $smarty->assign('action_link', array('text' => $_LANG['brokerage_order_list'], 'href' => 'merchants_commission.php?act=order_list&id=' . $user_id));

    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $gift_gard_log = get_gift_gard_log($id);

    $smarty->assign('full_page', 1);
    $smarty->assign('gift_gard_log', $gift_gard_log['pzd_list']);
    $smarty->assign('filter', $gift_gard_log['filter']);
    $smarty->assign('record_count', $gift_gard_log['record_count']);
    $smarty->assign('page_count', $gift_gard_log['page_count']);
    $smarty->assign('order_id', $id);

    $smarty->display("merchants_order_log.dwt");
}
/* ------------------------------------------------------ */
//-- 搜索用户
/* ------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'search_users') {
    $keywords = json_str_iconv(trim($_GET['keywords']));

    $sql = "SELECT u.user_name,msi.* FROM " . $ecs->table('merchants_shop_information') .
            " AS msi LEFT JOIN " . $ecs->table('users') . " AS u ON u.user_id = msi.user_id WHERE user_name LIKE '%" . mysql_like_quote($keywords) . "%' OR msi.user_id LIKE '%" . mysql_like_quote($keywords) . "%'";
    $row = $db->getAll($sql);

    make_json_result($row);
}
/* ------------------------------------------------------ */
//--商家订单列表  操作日志 ajax by kong 
/* ------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'Ajax_handle_log') {

    $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $gift_gard_log = get_gift_gard_log($id);

    $smarty->assign('gift_gard_log', $gift_gard_log['pzd_list']);
    $smarty->assign('filter', $gift_gard_log['filter']);
    $smarty->assign('record_count', $gift_gard_log['record_count']);
    $smarty->assign('page_count', $gift_gard_log['page_count']);

    make_json_result($smarty->fetch('merchants_order_log.htm'), '', array('filter' => $gift_gard_log['filter'], 'page_count' => $gift_gard_log['page_count']));
}
/* ------------------------------------------------------ */
//--店铺佣金冻结
/* ------------------------------------------------------ */
elseif($_REQUEST['act'] == 'commission_bill'){
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'commission_bill'));
    
    $user_id = isset($_REQUEST['id']) && empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $smarty->assign('user_id', $user_id);
    
    if(empty($user_id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }
    
    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['commission_bill']); // 当前导航
    $smarty->assign('full_page', 1); // 翻页参数
    
    $result = commission_bill_list();
    
    $smarty->assign('full_page', 1); // 翻页参数
    $smarty->assign('bill_list', $result['bill_list']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
    
    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }
    
    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_commission_bill.dwt');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'commission_bill_query')
{
    check_authz_json('merchants_commission');

    $result = commission_bill_list();

    $smarty->assign('bill_list',    $result['bill_list']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('merchants_commission_bill.dwt'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/*------------------------------------------------------ */
//-- 删除账单
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'bill_remove')
{
    check_authz_json('merchants_commission');
    
    $id = intval($_REQUEST['bill_id']);
    $seller_id = intval($_REQUEST['seller_id']);

    $sql = "DELETE FROM " . $ecs->table('seller_commission_bill') . "
		WHERE id = '$id'";
    $db->query($sql);
    
    $sql = "UPDATE " . $ecs->table('seller_bill_order') ." SET bill_id = 0 WHERE bill_id = '$id' AND chargeoff_status < 2";
    $db->query($sql);

    /* 记日志 */
    admin_log("删除账单", 'remove', 'seller_commission_bill');

    /* 清除缓存 */
    clear_cache_files();
    
    $url = 'merchants_commission.php?act=commission_bill_query&id=' .$seller_id. '&' . str_replace('act=bill_remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");

    exit;
}

/* ------------------------------------------------------ */
//--店铺佣金账单明细
/* ------------------------------------------------------ */
elseif($_REQUEST['act'] == 'bill_detail'){
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'commission_bill'));
    
    $user_id = isset($_REQUEST['seller_id']) && empty($_REQUEST['seller_id']) ? 0 : intval($_REQUEST['seller_id']);
    $smarty->assign('user_id', $user_id);
    
    if(empty($user_id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }
    
    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['commission_bill_detail']); // 当前导航
    $smarty->assign('full_page', 1); // 翻页参数
    
    $result = bill_detail_list();

    $smarty->assign('full_page', 1); // 翻页参数
    $smarty->assign('bill_list', $result['bill_list']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
    
    $bill_detail = array(
        'id' => $result['filter']['bill_id']
    );
    $bill = get_bill_detail($bill_detail);
    $smarty->assign('bill', $bill);
    
    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }
    
    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_bill_detail.dwt');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'bill_detail_query')
{
    check_authz_json('merchants_commission');

    $result = bill_detail_list();

    $smarty->assign('bill_list',    $result['bill_list']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('merchants_bill_detail.dwt'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/* ------------------------------------------------------ */
//--店铺佣金账单明细
/* ------------------------------------------------------ */
elseif($_REQUEST['act'] == 'bill_goods'){
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'commission_bill'));
    
    $user_id = !isset($_REQUEST['seller_id']) && empty($_REQUEST['seller_id']) ? 0 : intval($_REQUEST['seller_id']);
    $smarty->assign('user_id', $user_id);
    
    if(empty($user_id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }
    
    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['commission_bill_detail']); // 当前导航
    $smarty->assign('full_page', 1); // 翻页参数
    
    $result = bill_goods_list();
 
    $smarty->assign('full_page', 1); // 翻页参数
    $smarty->assign('goods_list', $result['goods_list']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
    
    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }
    
    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_bill_goods.dwt');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'bill_goods_query')
{
    check_authz_json('merchants_commission');

    $result = bill_goods_list();
    
    $smarty->assign('goods_list',    $result['goods_list']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('merchants_bill_goods.dwt'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/* ------------------------------------------------------ */
//--账单未确认收货订单
/* ------------------------------------------------------ */
elseif($_REQUEST['act'] == 'bill_notake_order'){
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'commission_bill'));
    
    $user_id = !isset($_REQUEST['seller_id']) && empty($_REQUEST['seller_id']) ? 0 : intval($_REQUEST['seller_id']);
    $smarty->assign('user_id', $user_id);
    
    if(empty($user_id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }
    
    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['commission_bill_detail']); // 当前导航
    $smarty->assign('full_page', 1); // 翻页参数
    
    $result = bill_notake_order_list();
 
    $smarty->assign('full_page', 1); // 翻页参数
    $smarty->assign('order_list', $result['order_list']);
    $smarty->assign('filter', $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count', $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
    
    $bill_detail = array(
        'id' => $result['filter']['bill_id']
    );
    $bill = get_bill_detail($bill_detail);
    $smarty->assign('bill', $bill);
    
    if (file_exists(MOBILE_DRP)) {
        $smarty->assign('is_dir', 1);
    } else {
        $smarty->assign('is_dir', 0);
    }
    
    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_bill_notake_order.dwt');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'bill_notake_order_query')
{
    check_authz_json('merchants_commission');

    $result = bill_notake_order_list();
    
    $smarty->assign('order_list',    $result['order_list']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('merchants_bill_notake_order.dwt'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/* ------------------------------------------------------ */
//--申请账单结算
/* ------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'apply_for') {
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'commission_bill'));
    
    $type = isset($_REQUEST['type']) && !empty($_REQUEST['type']) ? addslashes(trim($_REQUEST['type'])) : '';
    $smarty->assign('type', $type); // 冻结资金
    
    $smarty->assign('ur_here', $_LANG['commission_bill']); // 当前导航
    
    $bill_id = isset($_REQUEST['bill_id']) && !empty($_REQUEST['bill_id']) ? intval($_REQUEST['bill_id']) : 0;
    $smarty->assign('form_act', 'bill_update');
    
    $bill_detail = array(
        'id' => $bill_id
    );
    
    $bill = get_bill_detail($bill_detail);
    $smarty->assign('bill', $bill);
    $smarty->assign('user_id', $bill['seller_id']);
    
    if ($bill['chargeoff_status'] == 0 && $type != 'frozen') {
        $link[0] = array('href' => 'merchants_commission.php?act=commission_bill&id=' .$seller_id, 'text' => $_LANG['commission_bill']);
        sys_msg($sys_msg = $_LANG['apply_for_failure'], 0, $link);
    }
    
    /* 显示模板 */
    assign_query_info();
    $smarty->display('merchants_bill_applyfor.dwt');
}

/* ------------------------------------------------------ */
//--申请账单更新
/* ------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'bill_update') {
    
    /* 检查权限 */
    admin_priv('merchants_commission');
    
    $bill_id = isset($_REQUEST['bill_id']) && !empty($_REQUEST['bill_id']) ? intval($_REQUEST['bill_id']) : 0;
    $seller_id = isset($_REQUEST['seller_id']) && !empty($_REQUEST['seller_id']) ? intval($_REQUEST['seller_id']) : $adminru['ru_id'];
    $reject_note = isset($_REQUEST['reject_note']) && !empty($_REQUEST['reject_note']) ? addslashes($_REQUEST['reject_note']) : '';
    $check_status = isset($_REQUEST['check_status']) && !empty($_REQUEST['check_status']) ? intval($_REQUEST['check_status']) : 0;
    
    //冻结账单 start
    $frozen_money = isset($_REQUEST['frozen_money']) && !empty($_REQUEST['frozen_money']) ? floatval($_REQUEST['frozen_money']) : 0;
    $frozen_data = isset($_REQUEST['frozen_data']) && !empty($_REQUEST['frozen_data']) ? intval($_REQUEST['frozen_data']) : 0;
    $add_sub_frozen_money = isset($_REQUEST['add_sub_frozen_money']) ? addslashes(trim($_REQUEST['add_sub_frozen_money'])) : 1;
    $add_sub_frozen_data = isset($_REQUEST['add_sub_frozen_data']) ? addslashes(trim($_REQUEST['add_sub_frozen_data'])) : 1;
    $type = isset($_REQUEST['type']) && !empty($_REQUEST['type']) ? addslashes(trim($_REQUEST['type'])) : '';
    //冻结账单 end
    
    if(empty($seller_id) || empty($bill_id)){
        $Loaction = "merchants_commission.php?act=list";
        ecs_header("Location: $Loaction\n");
        exit;
    }
    
    $bill_detail = array(
        'id' => $bill_id
    );
    
    /* 结账时间 */
    $settleaccounts_time = 0;
    if($check_status == 1){
        $settleaccounts_time = gmtime();
        $chargeoff_status = 2;
    }else{
        $chargeoff_status = 3;
    }

    $bill = get_bill_detail($bill_detail);
    
    /* 扣除已单独结算的账单订单佣金 start */
    $bill['should_amount'] = $bill['should_amount'] - $bill['settle_accounts'];
    
    if($bill['should_amount'] < 0){
        $bill['should_amount'] = 0;
    }
    /* 扣除已单独结算的账单订单佣金 end */
    
    $gmtime = gmtime();
    if($type == 'bill_frozen' || $type == 'bill_unfreeze')
    {
        
        //冻结账单金额
        if ($type == 'bill_frozen') 
        {
            if ($bill['chargeoff_status'] != 2) {
                $frozen_money = $frozen_money * $add_sub_frozen_money;
                $frozen_data = $frozen_data * $add_sub_frozen_data;

                $frozen['should_amount'] = $bill['should_amount'] - $frozen_money;
                $frozen['frozen_money'] = $bill['frozen_money'] + $frozen_money;
                $frozen['frozen_data'] = $bill['frozen_data'] + $frozen_data;
                $frozen['frozen_time'] = gmtime();

                if ($frozen['frozen_data'] < 0) {
                    $frozen['frozen_data'] = 0;
                }
                $sys_msg = $_LANG['update_bill_success'];
            } else {
                $sys_msg = $_LANG['update_bill_failure'];
            }
        } 
        
        //解冻账单金额 
        else 
        {
            if ($bill['chargeoff_status'] == 2) {
                if ($frozen_money >= $bill['frozen_money']) {
                    $frozen_money = $bill['frozen_money'];
                    $frozen['frozen_data'] = 0;
                }

                $frozen['should_amount'] = $bill['should_amount'] + $frozen_money;
                $frozen['frozen_money'] = $bill['frozen_money'] - $frozen_money;

                $sql = "UPDATE " . $ecs->table('seller_shopinfo') . " SET seller_money = seller_money + '" . $frozen_money . "' WHERE ru_id = '" . $bill['seller_id'] . "'";
                $db->query($sql);

                $change_desc = sprintf($_LANG['seller_bill_unfreeze'], $_SESSION['admin_name']);
                $user_account_log = array(
                    'user_id' => $bill['seller_id'],
                    'user_money' => $frozen_money,
                    'change_time' => $gmtime,
                    'change_desc' => $change_desc,
                    'change_type' => 2
                );
                $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_account_log'), $user_account_log, 'INSERT');
                
                $sys_msg = $_LANG['update_bill_success'];
            }else{
                $sys_msg = $_LANG['update_bill_failure'];
            }
        }

        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('seller_commission_bill'), $frozen, 'UPDATE', "id = '$bill_id'");
        
    } else {
        
        //结算账单
        
        $sql = "UPDATE " . $ecs->table('seller_commission_bill') . " SET actual_amount = actual_amount + '" . $bill['should_amount'] . "' WHERE id = '" . $bill['id'] . "'";
        $db->query($sql);

        if ($bill['bill_apply'] == 1) {
            $other = array(
                'chargeoff_status' => $chargeoff_status,
                'reject_note' => $reject_note,
                'check_status' => $check_status,
                'check_time' => gmtime(),
                'settleaccounts_time' => $settleaccounts_time
            );

            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('seller_commission_bill'), $other, 'UPDATE', "id = '$bill_id'");
            
            $sys_msg = $_LANG['update_bill_success'];

            /* 佣金结算 start */
            $apply_sn = sprintf($_LANG['seller_bill_account'], $bill['bill_sn']);
            $other['apply_sn'] = $apply_sn;
            $other['admin_id'] = $_SESSION['admin_id'];
            $other['ru_id'] = $bill['seller_id'];
            $other['order_id'] = 0;
            $other['amount'] = $bill['should_amount']; //结算金额
            $other['add_time'] = $gmtime; //结算时间
            $other['log_type'] = 2;
            $other['is_paid'] = 1;

            $db->autoExecute($ecs->table('seller_account_log'), $other, 'INSERT');

            $sql = "UPDATE " . $ecs->table('seller_shopinfo') . " SET seller_money = seller_money + '" . $bill['should_amount'] . "' WHERE ru_id = '" . $bill['seller_id'] . "'";
            $db->query($sql);

            $change_desc = sprintf($_LANG['seller_bill_settlement'], $_SESSION['admin_name']);
            $user_account_log = array(
                'user_id' => $bill['seller_id'],
                'user_money' => $bill['should_amount'],
                'change_time' => $gmtime,
                'change_desc' => $change_desc,
                'change_type' => 2
            );
            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_account_log'), $user_account_log, 'INSERT');
            /* 佣金结算 end */

            /* 更新订单已结算状态 start */
            $order_other['chargeoff_status'] = 2;
            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('seller_bill_order'), $order_other, 'UPDATE', "bill_id = '$bill_id'");

            $sql = "SELECT GROUP_CONCAT(order_id) AS order_id FROM " . $GLOBALS['ecs']->table('seller_bill_order') . " WHERE bill_id = '$bill_id'";
            $order = $GLOBALS['db']->getRow($sql);

            if ($order && $order['order_id']) {
                $order_other['is_settlement'] = 1;
                $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order_other, 'UPDATE', "order_id IN(" .$order['order_id']. ")");
            }
            /* 更新订单已结算状态 end */
        } else {
            $sys_msg = $_LANG['update_bill_failure'];
        }
    }

    $link[0] = array('href' => 'merchants_commission.php?act=commission_bill&id=' .$seller_id, 'text' => $_LANG['commission_bill']);
    sys_msg($sys_msg, 0, $link);
}

function order_download_list($result) {
    if (empty($result)) {
        return i("没有符合您要求的数据！^_^");
    }
    
    $downLang = $GLOBALS['_LANG']['down']['order_sn'] . "," .
                $GLOBALS['_LANG']['down']['short_order_time'] . "," .
                $GLOBALS['_LANG']['down']['consignee_address'] . "," .
                $GLOBALS['_LANG']['down']['total_fee'] . "," .
                $GLOBALS['_LANG']['down']['shipping_fee'] . "," .
                $GLOBALS['_LANG']['down']['discount'] . "," .
                $GLOBALS['_LANG']['down']['coupons'] . "," .
                $GLOBALS['_LANG']['down']['integral_money'] . "," .
                $GLOBALS['_LANG']['down']['bonus'] . "," .
                $GLOBALS['_LANG']['down']['return_amount_price'] . "," .
                $GLOBALS['_LANG']['down']['brokerage_amount_price'] . "," .
                $GLOBALS['_LANG']['down']['effective_amount_price'] . "," .
                $GLOBALS['_LANG']['down']['settlement_status'] . "," .
                $GLOBALS['_LANG']['down']['ordersTatus'];
    
    $data = i($downLang . "\n");
    $count = count($result);

    for ($i = 0; $i < $count; $i++) {
        $order_sn = i('#'.$result[$i]['order_sn']);//订单号前加'#',避免被四舍五入 by wu
        $short_order_time = i($result[$i]['short_order_time']);
        $consignee = i($result[$i]['consignee']) . "" . i($result[$i]['address']);
        $total_fee = i($result[$i]['order_total_fee']);
        $shipping_fee = i($result[$i]['shipping_fee']);
        $discount = i($result[$i]['discount']);
        $coupons = i($result[$i]['coupons']);
        $integral_money = i($result[$i]['integral_money']);
        $bonus = i($result[$i]['bonus']);
        $return_amount_price = i($result[$i]['return_amount_price']);
        $brokerage_amount_price = i($result[$i]['brokerage_amount_price']);
        $effective_amount_price = i($result[$i]['effective_amount_price'] + $result[$i]['shipping_fee']);
        $is_settlement = i($result[$i]['settlement_status']);
        $status = i($result[$i]['ordersTatus']);
        
        if ($consignee) {
            $consignee = str_replace(array(",", "，"), "_", $consignee);
        }

        $data .= $order_sn . ',' . $short_order_time . ',' . $consignee . ',' .$total_fee . ',' . $shipping_fee . ',' . 
                $discount . ',' . $coupons . ',' . $integral_money . ',' . $bonus . ',' . $return_amount_price . ',' . $brokerage_amount_price . ',' .
                $status . ',' . $effective_amount_price . ',' . $is_settlement . ',' . $is_frozen . "\n";
    }
    return $data;
}

/**
 *  获取商家佣金列表
 *
 * @access  public
 * @param  is_pagination  是否分页
 *
 * @return void
 */
function merchants_commission_list() {
    $adminru = get_admin_ru_id();

    $result = get_filter();
    if ($result === false) {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;
        
        $filter['user_name'] = !isset($_REQUEST['user_name']) && empty($_REQUEST['user_name']) ?  '' : trim($_REQUEST['user_name']);
        
        if (!empty($_GET['is_ajax']) && $_GET['is_ajax'] == 1)
        {
            $filter['user_name'] = json_str_iconv($filter['user_name']);
        }
        
        /* 过滤信息 */
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'mis.user_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);

        $where = 'WHERE 1 ';
        
        if($filter['user_name']){
            $sql = "SELECT GROUP_CONCAT(user_id) AS user_id FROM " .$GLOBALS['ecs']->table('users'). " WHERE user_name LIKE '%" . mysql_like_quote($filter['user_name']) . "%'";
            $user_list = $GLOBALS['db']->getOne($sql);
            
            $where .= " AND mis.user_id " . db_create_in($user_list);
        }
        
        //管理员查询的权限 -- 店铺查询 start
        $filter['store_search'] = empty($_REQUEST['store_search']) ? 0 : intval($_REQUEST['store_search']);
        $filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
        $filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';

        $store_search_where = '';
        if ($filter['store_search'] != 0) {
            if ($adminru['ru_id'] == 0) {

                if ($_REQUEST['store_type']) {
                    $store_search_where = "AND msi.shopNameSuffix = '" . $_REQUEST['store_type'] . "'";
                }

                if ($filter['store_search'] == 1) {
                    $where .= " AND mis.user_id = '" . $filter['merchant_id'] . "' ";
                } elseif ($filter['store_search'] == 2) {
                    $where .= " AND mis.rz_shopName LIKE '%" . mysql_like_quote($filter['store_keyword']) . "%'";
                } elseif ($filter['store_search'] == 3) {
                    $where .= " AND mis.shoprz_brandName LIKE '%" . mysql_like_quote($filter['store_keyword']) . "%' " . $store_search_where;
                }
            }
        }

        $where .= " AND mis.merchants_audit = 1 ";
        //管理员查询的权限 -- 店铺查询 end

        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        } elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        } else {
            $filter['page_size'] = 15;
        }

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('merchants_shop_information') . " as mis " . $where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */  //ecmoban模板堂 -zhuo suppliers_sn		
        $sql = "SELECT mis.*, msf.companyName, msf.company_adress, msf.company_contactTel, s.server_id, s.suppliers_desc, s.suppliers_percent  
                FROM " . $GLOBALS['ecs']->table("merchants_shop_information") . " as mis " .
                " LEFT JOIN " . $GLOBALS['ecs']->table('merchants_server') . " as s on mis.user_id = s.user_id " .
                " LEFT JOIN " . $GLOBALS['ecs']->table('merchants_steps_fields') . " as msf on s.user_id = msf.user_id " .
                " $where " . " group by mis.user_id " .
                " ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
        
        $sql .= " LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";
        set_filter($filter, $sql);
    } else {
        $sql = $result['sql'];
        $filter = $result['filter'];
    }

    $row = $GLOBALS['db']->getAll($sql);
    
    $count = count($row);
    for ($i = 0; $i < $count; $i++) {
        
        $row[$i]['user_name'] = $GLOBALS['db']->getOne("SELECT user_name FROM " .$GLOBALS['ecs']->table('users'). " WHERE user_id = '" .$row[$i]['user_id']. "'");
        
        $row[$i]['server_id'] = $row[$i]['server_id'];
        $valid = get_merchants_order_valid_refund($row[$i]['user_id']); //订单有效总额
       
        $row[$i]['order_valid_total'] = price_format($valid['total_fee']);
        $row[$i]['is_goods_rate'] = $valid['is_goods_rate'];
        
        $row[$i]['order_total_fee'] = $valid['order_total_fee'];
        $row[$i]['goods_total_fee'] = $valid['goods_total_fee'];

        /* 微分销 */
        if (file_exists(MOBILE_DRP)) {
            $row[$i]['order_drp_commission'] = price_format($valid['drp_money']); //dis liu	
        }

        $refund = get_merchants_order_valid_refund($row[$i]['user_id'], 1); //订单退款总额
        $row[$i]['order_refund_total'] = price_format($refund['total_fee']);
        $row[$i]['store_name'] = get_shop_name($row[$i]['user_id'], 1);

        /* 微分销 */
        if (file_exists(MOBILE_DRP)) {
            $is_settlement = merchants_is_settlement($row[$i]['user_id'], 1); //已结算佣金金额  liu
            $row[$i]['is_settlement'] = $is_settlement['all'];

            $no_settlement = merchants_is_settlement($row[$i]['user_id'], 0); //未结算佣金金额  liu
            $row[$i]['no_settlement'] = $no_settlement['all'];
        } else {
            $is_settlement = merchants_is_settlement($row[$i]['user_id'], 1); //已结算佣金金额  liu
            $row[$i]['is_settlement'] = price_format($is_settlement);

            $no_settlement = merchants_is_settlement($row[$i]['user_id'], 0); //未结算佣金金额  liu
            $row[$i]['no_settlement'] = price_format($no_settlement);
        }

        $row[$i]['total_fee_price'] = number_format($valid['total_fee'], 2, '.', '');
        $row[$i]['total_fee_refund'] = number_format($refund['total_fee'], 2, '.', '');
        $row[$i]['is_settlement_price'] = $is_settlement; //已结算佣金金额  liu
        $row[$i]['no_settlement_price'] = $no_settlement; //未结算佣金金额  liu

        $sql = "SELECT ss.shop_name, ss.shop_address, ss.mobile, " .
                "concat(IFNULL(p.region_name, ''), " .
                "'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region " .
                " FROM " . $GLOBALS['ecs']->table('seller_shopinfo') . " AS ss " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS p ON ss.province = p.region_id " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS t ON ss.city = t.region_id " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS d ON ss.district = d.region_id " .
                " WHERE ss.ru_id = '" . $row[$i]['user_id'] . "' LIMIT 1";
        $seller_shopinfo = $GLOBALS['db']->getRow($sql);

        if ($seller_shopinfo['shop_name']) {
            $row[$i]['companyName'] = $seller_shopinfo['shop_name'];
            $row[$i]['company_adress'] = "[" . $seller_shopinfo['region'] . "] " . $seller_shopinfo['shop_address'];
        }

        if ($seller_shopinfo['mobile']) {
            $row[$i]['company_contactTel'] = $seller_shopinfo['mobile'];
        } else {
            $row[$i]['company_contactTel'] = $row[$i]['contactPhone'];
        }
    }

    $arr = array('result' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
}

//佣金百分比
function get_suppliers_percent() {
    $sql = "select percent_id, percent_value from " . $GLOBALS['ecs']->table('merchants_percent') . " where 1 order by sort_order asc";
    $res = $GLOBALS['db']->getAll($sql);

    return $res;
}

/**
 *  获取商家佣金列表
 *
 * @access  public
 * @param   is_pagination  是否分页
 *
 * @return void
 */
function merchants_order_list($page = 0) {
    $result = get_filter();
    if ($result === false) {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

        /* 过滤信息 */
        $filter['id'] = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        
        $filter['order_sn'] = !isset($_REQUEST['order_sn']) && empty($_REQUEST['order_sn']) ?  '' : dsc_addslashes($_REQUEST['order_sn']);
        $filter['consignee'] = !isset($_REQUEST['consignee']) && empty($_REQUEST['consignee']) ?  '' : dsc_addslashes($_REQUEST['consignee']);
        $filter['order_cat'] = !isset($_REQUEST['order_cat']) && empty($_REQUEST['order_cat']) ?  '' : dsc_addslashes($_REQUEST['order_cat']);
        
        $filter['order_status'] = !isset($_REQUEST['order_status']) && empty($_REQUEST['order_status']) ?  0 : intval($_REQUEST['order_status']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'o.order_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : local_strtotime(trim($_REQUEST['start_time']));
        $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : local_strtotime(trim($_REQUEST['end_time']));
        $filter['state'] = isset($_REQUEST['state']) ? trim($_REQUEST['state']) : ''; //liu 新增状态
        
        $commission_info = get_seller_commission_info($filter['id']);
        
        if ($commission_info && $commission_info['percent_value']) {
            $percent_value = $commission_info['percent_value'] / 100;
        } else {
            $percent_value = 1;
        }
        
        $where = 'WHERE 1';
        
        if ($filter['order_sn'])
        {
            $where .= " AND o.order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%'";
        }
        
        if ($filter['consignee'])
        {
            $where .= " AND o.consignee LIKE '%" . mysql_like_quote($filter['consignee']) . "%'";
        }
        
        if ($filter['order_cat']) {
            switch ($filter['order_cat']) {
                case 'stages':
                    $where .= " AND (SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('baitiao_log'). " AS b WHERE b.order_id = o.order_id) > 0 ";
                    break;
                case 'zc':
                    $where .= " AND o.is_zc_order = 1 ";
                    break;
                case 'store':
                    $where .= " AND (SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('store_order'). " AS s WHERE s.order_id = o.order_id) > 0 ";
                    break;
                case 'other':
                    $where .= " AND length(o.extension_code) > 0 ";
                    break;
                case 'dbdd':
                    $where .= " AND o.extension_code = 'snatch' ";
                    break;
                case 'msdd':
                    $where .= " AND o.extension_code = 'seckill' ";
                    break;
                case 'tgdd':
                    $where .= " AND o.extension_code = 'group_buy' ";
                    break;
                case 'pmdd':
                    $where .= " AND o.extension_code = 'auction' ";
                    break;
                case 'jfdd':
                    $where .= " AND o.extension_code = 'exchange_goods' ";
                    break;
                case 'ysdd':
                    $where .= " AND o.extension_code = 'presale' ";
                    break;
                default:
            }
        }
        
        if (isset($filter['state']) && $filter['state'] > -1 && !empty($filter['state'])) {// liu  判断是否有结算状态查询
            $where .= " AND is_settlement = '" . $filter['state'] . "' ";
        }
        
        if (!empty($filter['start_time'])) {
            $where .= " AND o.add_time >= '" . $filter['start_time'] . "' ";
        }

        if (!empty($filter['end_time'])) {
            $where .= " AND o.add_time <= '" . $filter['end_time'] . "' ";
        }

        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
        if($page > 0){
            $filter['page'] = $page;
        }
        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        } elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        } else {
            $filter['page_size'] = 15;
        }
        
        $where .= order_query_sql('confirm_take', 'o.');
        
        $where .= " AND (SELECT count(*) FROM " . $GLOBALS['ecs']->table('order_info') . " AS oi2 WHERE oi2.main_order_id = o.order_id) = 0 ";  //主订单下有子订单时，则主订单不显示
        $where .= " AND (SELECT og.ru_id FROM " . $GLOBALS['ecs']->table('order_goods') . ' AS og' . " WHERE og.order_id = o.order_id LIMIT 1) = '" . $filter['id'] . "' ";

        /* 记录总数 */
        $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('order_info') . " AS o " . $where;

        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
        $sql = "SELECT o.is_frozen,o.order_id, o.main_order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement," .
                "(" . order_amount_field('o.') . ") AS order_total_fee," .
                "o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id, o.goods_amount, o.shipping_fee, " . //佣金比率 by wu
                "(" . order_commission_field('o.') . ") AS total_fee, o.discount, o.coupons, o.integral_money, o.bonus, o.user_id, " .
                "(" . order_activity_field_add('o.') . ") AS activity_fee " .
                " FROM " . $GLOBALS['ecs']->table('order_info') . " AS o " .
                $where .
                " ORDER BY $filter[sort_by] $filter[sort_order] ";
        $sql .= " LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";
        set_filter($filter, $sql);
    } else {
        $sql = $result['sql'];
        $filter = $result['filter'];
    }

    $row = $GLOBALS['db']->getAll($sql);
    
    $count = count($row);
    for ($i = 0; $i < $count; $i++) {
        
        //查会员名称
        $sql = " SELECT user_name FROM ".$GLOBALS['ecs']->table('users')." WHERE user_id = '".$row[$i]['user_id']."'";
        $row[$i]['buyer'] = $GLOBALS['db']->getOne($sql, true);
        $row[$i]['buyer'] = !empty($row[$i]['buyer']) ? $row[$i]['buyer'] : $GLOBALS['_LANG']['anonymous'];
        
        $filter['commission_model'] = $commission_info['commission_model'];
        $filter['percent_value'] = $percent_value * 100;
        
        $row[$i]['formated_order_amount'] = price_format($row[$i]['order_amount'], true);
        $row[$i]['formated_money_paid'] = price_format($row[$i]['money_paid'], true);
        $row[$i]['formated_total_fee'] = price_format($row[$i]['total_fee'], true);
        $row[$i]['short_order_time'] = local_date($GLOBALS['_CFG']['time_format'], $row[$i]['add_time']);
        $row[$i]['ordersTatus'] = $GLOBALS['_LANG']['os'][$row[$i]['order_status']] . "|" . $GLOBALS['_LANG']['ps'][$row[$i]['pay_status']] . "|" . $GLOBALS['_LANG']['ss'][$row[$i]['shipping_status']];
        $row[$i]['formated_discount'] = price_format($row[$i]['discount'], true);
        $row[$i]['formated_coupons'] = price_format($row[$i]['coupons'], true);
        $row[$i]['formated_integral_money'] = price_format($row[$i]['integral_money'], true);
        $row[$i]['formated_bonus'] = price_format($row[$i]['bonus'], true);
        $row[$i]['formated_order_total_fee'] = price_format($row[$i]['order_total_fee'] - $row[$i]['discount'], true);
        $row[$i]['formated_order_amount_field'] = price_format($row[$i]['total_fee'] + $row[$i]['shipping_fee'], true);
        $row[$i]['formated_shipping_fee'] = price_format($row[$i]['shipping_fee'], true);
        if ($row[$i]['is_settlement']) {
            $row[$i]['settlement_status'] = "已结算";
        } else {
            $row[$i]['settlement_status'] = "未结算";
        }
        $row[$i]['settlement_frozen'] = "";
        if ($row[$i]['is_frozen']) {
            $row[$i]['settlement_frozen'] = "冻结";
        }

        $row[$i]['consignee'] = "【" . $row[$i]['consignee'] . "】";

        $row[$i]['return_amount'] = get_order_return_list($row[$i]['order_id']);
        $row[$i]['return_amount'] = !empty($row[$i]['return_amount']) ? $row[$i]['return_amount'] : "0.00";

        $row[$i]['formated_return_amount'] = price_format($row[$i]['return_amount'], true);
        
        $row[$i]['is_goods_rate'] = 0;
        
        $order = array(
            'goods_amount' => $row[$i]['goods_amount'],
            'activity_fee' => $row[$i]['activity_fee']
        );

        /* 微分销 */
        if (file_exists(MOBILE_DRP)) {

            $brokerage_amount = get_order_drp_money($row[$i]['total_fee'], $filter['id'], $row[$i]['order_id'], $order);
            
            if ($row[$i]['total_fee'] > 0) {
                $total_return_amount = $brokerage_amount['total_fee'] - $row[$i]['return_amount'];
            } else {
                $total_return_amount = 0;
            }

            /* 佣金比率 by wu start */
            if ($commission_info['commission_model']) {
                $order_goods_commission = get_order_goods_commission($row[$i]['order_id']);
                
                if ($row[$i]['goods_amount'] <= 0) {
                    $row[$i]['goods_amount'] = 1;
                }
               
                $order_commission = $order_goods_commission * $total_return_amount / ($row[$i]['goods_amount'] - $brokerage_amount['rate_activity']) + $brokerage_amount['should_amount'];
                $row[$i]['formated_brokerage_amount'] = price_format($order_commission + $row[$i]['shipping_fee'], true);
                
                $effective_amount_price = $order_commission;
                $row[$i]['effective_amount_price'] = number_format($effective_amount_price, 2, '.', '');
            } else {
                $row[$i]['formated_brokerage_amount'] = price_format($total_return_amount * $percent_value + $row[$i]['shipping_fee'] + $brokerage_amount['should_amount'], true);
                
                $effective_amount_price = $total_return_amount * $percent_value + $brokerage_amount['should_amount'];
                $row[$i]['effective_amount_price'] = number_format($effective_amount_price, 2, '.', '');
            }
            
            if($brokerage_amount['should_amount'] > 0){
                $row[$i]['is_goods_rate'] = 1;
            }
            
            /* 佣金比率 by wu end */
            $row[$i]['formated_effective_amount'] = price_format($total_return_amount, true);
            $row[$i]{'formated_drp_commission'} = price_format($row[$i]['drp_money'], true);
            $row[$i]['brokerage_amount_price'] = $total_return_amount;
            $row[$i]['formated_drp_commission'] = price_format($brokerage_amount['drp_money'], true);
            
            $row[$i]['total_fee'] = $brokerage_amount['total_fee'];
        } else {
            
            /* 商品佣金比例 start */
            $goods_rate = get_alone_goods_rate($row[$i]['order_id'], 0, $order);
            
            if($goods_rate['should_amount'] > 0){
                $row[$i]['is_goods_rate'] = 1;
            }
            
            if ($goods_rate['rate_activity']) {
                $row[$i]['total_fee'] = $row[$i]['total_fee'] + $goods_rate['rate_activity'];
            }
            
            /**
             * 扣除单独设置商品佣金比例的商品总金额
             */
            if ($goods_rate && $goods_rate['total_fee']) {
                $row[$i]['total_fee'] = $row[$i]['total_fee'] - $goods_rate['total_fee'];
            }
            /* 商品佣金比例 end */
            
            if ($row[$i]['total_fee'] > 0) {
                $total_return_amount = $row[$i]['total_fee'] - $row[$i]['return_amount'];
            } else {
                $total_return_amount = 0;
            }

            /* 佣金比率 by wu start */
            if ($commission_info['commission_model']) {
                $order_goods_commission = get_order_goods_commission($row[$i]['order_id']);
                
                if ($row[$i]['goods_amount'] <= 0) {
                    $row[$i]['goods_amount'] = 1;
                }
                
                $order_commission = $order_goods_commission * $total_return_amount / $row[$i]['goods_amount'] + $goods_rate['should_amount'];
                $row[$i]['formated_brokerage_amount'] = price_format($order_commission + $row[$i]['shipping_fee'], true);
                
                $effective_amount_price = $order_commission;
                $row[$i]['effective_amount_price'] = number_format($effective_amount_price, 2, '.', '');
            } else {
                $row[$i]['formated_brokerage_amount'] = price_format($total_return_amount * $percent_value + $row[$i]['shipping_fee'] + $goods_rate['should_amount'], true);
                
                $effective_amount_price = $total_return_amount * $percent_value + $goods_rate['should_amount'];
                $row[$i]['effective_amount_price'] = number_format($effective_amount_price, 2, '.', '');
            }
            /* 佣金比率 by wu end */
            $row[$i]['formated_effective_amount'] = price_format($total_return_amount, true);
            $row[$i]['brokerage_amount_price'] = $total_return_amount;
        }
        
        $row[$i]['formated_effective_amount_price'] = price_format($row[$i]['effective_amount_price'], true);

        $row[$i]['total_fee_price'] = $row[$i]['total_fee'];
        $row[$i]['return_amount_price'] = $row[$i]['return_amount'];
    }

    if ($count) {
        /* 微分销 */
        if (file_exists(MOBILE_DRP)) {
            $is_settlement = merchants_is_settlement($filter['id'], 1, $filter); //已结算佣金金额  liu
            $is_settlement = $is_settlement['all'];

            $no_settlement = merchants_is_settlement($filter['id'], 0, $filter); //未结算佣金金额  liu
            $no_settlement = $no_settlement['all'];

            $all_commission = merchants_is_settlement($filter['id'], '', $filter); //所有佣金总额
            
            $row['brokerage_amount']['all_price'] = number_format($all_commission['all_prcie'], 2, '.', '');
            $row['brokerage_amount']['all'] = $all_commission['all']; //liu 
            $row['brokerage_amount']['all_drp'] = $all_commission['all_drp']; //liu 

            $row['brokerage_amount']['is_settlement'] = $is_settlement; //liu
            $row['brokerage_amount']['no_settlement'] = $no_settlement; //liu
        } else {
            $is_settlement = merchants_is_settlement($filter['id'], 1, $filter); //已结算佣金金额  liu
            $no_settlement = merchants_is_settlement($filter['id'], 0, $filter); //未结算佣金金额  liu

            $all_commission = merchants_is_settlement($filter['id'], '', $filter); //所有佣金总额

            $row['brokerage_amount']['is_settlement'] = price_format($is_settlement, true); //liu
            $row['brokerage_amount']['no_settlement'] = price_format($no_settlement, true); //liu
            
            $row['brokerage_amount']['all_price'] = number_format($all_commission, 2, '.', '');
            $row['brokerage_amount']['all'] = price_format($all_commission, true); //liu 

            $row['brokerage_amount']['is_settlement_price'] = $is_settlement; //liu
            $row['brokerage_amount']['no_settlement_price'] = $no_settlement; //liu
        }
    }

    $arr = array('orders' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 *  获取选中的订单佣金列表 liu
 *
 * @access  public
 * @param
 *
 * @return void
 */
function merchants_order_list_checked($ids) {

    $where = 'WHERE 1';
    $where .= " and o.is_settlement = 0 ";
    $where .= " and o.order_id " . db_create_in($ids);
    $where .= order_query_sql('finished', 'o.');
    $where .= " and (select count(*) from " . $GLOBALS['ecs']->table('order_info') . " as oi2 where oi2.main_order_id = o.order_id) = 0 ";  //主订单下有子订单时，则主订单不显示


    /* 查询 */
    $sql = "SELECT o.order_id, o.main_order_id FROM " . $GLOBALS['ecs']->table('order_info') . " AS o " .
            $where;
    $row = $GLOBALS['db']->getAll($sql);

    return $row;
}

/*
 * 获得字符串内的数字
 */

function findNum($str = '') {
    $str = trim($str);
    if (empty($str)) {
        return '';
    }
    $temp = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $result = '';
    for ($i = 0; $i < strlen($str); $i++) {
        if (in_array($str[$i], $temp)) {
            $result.=$str[$i];
        }
    }
    if ($result == '000') {
        $result = 0;
    }
    return $result;
}

/*
 *  字符串转换已结算未结算
 */

function changeSettlement($str) {
    if ($str == '0') {
        $str = '未结算';
    } else {
        $str = '已结算';
    }
    return $str;
}

function commission_download_list($result) {
    if (empty($result)) {
        return i("没有符合您要求的数据！^_^");
    }

    $data = i('商家名称,店铺名称,公司名称,公司地址,联系方式,订单有效总金额,订单退款总金额,已结算订单金额,未结算订单金额' . "\n");
    $count = count($result);

    for ($i = 0; $i < $count; $i++) {
        $user_name = i($result[$i]['user_name']);
        $store_name = i($result[$i]['store_name']);
        $companyName = i($result[$i]['companyName']);
        $company_adress = i($result[$i]['company_adress']);
        $company_contactTel = i($result[$i]['company_contactTel']);
        $order_valid_total = i($result[$i]['total_fee_price']);
        $order_refund_total = i($result[$i]['total_fee_refund']);
        $is_settlement = i(isset($result[$i]['is_settlement_price']['all_brokerage_amount']) ? $result[$i]['is_settlement_price']['all_brokerage_amount'] : 0.00);
        $no_settlement = i(isset($result[$i]['no_settlement_price']['all_brokerage_amount']) ? $result[$i]['no_settlement_price']['all_brokerage_amount'] : 0.00);
        
        if ($company_adress) {
            $company_adress = str_replace(array(",", "，"), "_", $company_adress);
        }

        $data .= $user_name . ',' . $store_name . ',' . $companyName . ',' .
                $company_adress . ',' . $company_contactTel . ',' . $order_valid_total . ',' .
                $order_refund_total . ',' . $is_settlement . ',' . $no_settlement . "\n";
    }
    return $data;
}

function i($strInput) {
    return iconv('utf-8', 'gb2312', $strInput); //页面编码为utf-8时使用，否则导出的中文为乱码
}

?>