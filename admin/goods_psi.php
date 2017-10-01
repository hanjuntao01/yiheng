<?php

/**
 * DSC 商品管理程序
 * ============================================================================
 * * 版权所有 2005-2017 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liu $
 * $Id: goods_psi.php 17217 2017-08-03 16:29:00 liu $
*/
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/cls_json.php');

/* 管理员ID */
$admin_id = get_admin_id();
$adminru = get_admin_ru_id();
if($adminru['ru_id'] == 0){
        $smarty->assign('priv_ru',   1);
}else{
        $smarty->assign('priv_ru',   0);
}

$exc   = new exchange($ecs->table("goods_inventory_logs"), $db, 'id', 'goods_id');

/*------------------------------------------------------ */
//-- 商品入库（进）
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'purchase')
{
	admin_priv('goods_psi');
	$smarty->assign('menu_select', array('action' => '02_goods_storage', 'current' => '01_psi_purchase'));
	$smarty->assign('ur_here', $_LANG['01_psi_purchase']);
	$warehouse_list = area_warehouse_list(0, 1);// $parent_id = 0,顶级仓库 $param = 1 只取同级仓库不获取子仓库地区
	$smarty->assign('warehouse_list', $warehouse_list);
	$suppliers_list = psi_suppliers_list();
	$smarty->assign('suppliers_list', $suppliers_list);
	$smarty->assign('full_page', 1);
	
	$smarty->display('psi_purchase.dwt');
}

/*------------------------------------------------------ */
//-- 商品入库（操作）
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'purchase_operate')
{
	admin_priv('goods_psi');
	$goods_ids 		= !empty($_REQUEST['goods_id'])				? $_REQUEST['goods_id']				: '';
	$goods_number 	= !empty($_REQUEST['goods_number']) 		? $_REQUEST['goods_number'] 		: '';
	$product_number = !empty($_REQUEST['product_number']) 		? $_REQUEST['product_number'] 		: '';
	$batch_number 	= !empty($_REQUEST['batch_number']) 		? $_REQUEST['batch_number'] 		: '';
	$region_id 		= !empty($_REQUEST['region_id']) 			? intval($_REQUEST['region_id'])	: '';
	$area_ids		= !empty($_REQUEST['area_id']) 				? $_REQUEST['area_id'] 				: '';
	$suppliers_id 	= !empty($_REQUEST['suppliers_id']) 		? intval($_REQUEST['suppliers_id'])	: '';
	$remark		    = !empty($_REQUEST['remark']) 				? $_REQUEST['remark'] 				: '';
	$user_id 		= $adminru['ru_id'];
	$result			= array('msg'=>'', 'error'=>'');

	if($goods_ids){
		if($goods_number)
		{
			foreach($goods_number as $k=>$v){
				//商品模式
				$warehouse_id = 0;
				$area_id = isset($area_ids[$k]) ? intval($area_ids[$k]) : 0;
				$where = " WHERE 1 ";
					   
				/* 区分商品模式改变仓库库存 */
				$goods = get_goods_model($k);
				$warehouse_list = get_warehouse_list();
				if($warehouse_list){
					$warehouse_id = $region_id;
					if(empty($area_id)){
						$sql = "SELECT rw.region_id FROM " .$GLOBALS['ecs']->table('region_warehouse'). " AS rw LEFT JOIN " .
						$GLOBALS['ecs']->table('warehouse_area_goods'). " AS wag ON rw.region_id = wag.region_id WHERE  rw.parent_id = '$region_id' AND rw.region_type = 1 AND wag.goods_id = '$k' ";
						$area_id = $GLOBALS['db']->getOne($sql, true);			
					}	
				}
				if($goods['model_attr'] == 1 && $region_id){
					$table = "warehouse_goods";
					$where .= " AND region_id = '$region_id'";
					$set = " region_number = region_number + '$v' ";
				}else{
					$table = "goods";
					$set = " goods_number = goods_number + '$v' ";
				}				
				
				$sql = " UPDATE " . $ecs->table($table) . " SET $set $where AND goods_id = '$k' ";
				
				if($db->query($sql)){
					//插入日志
					$new_log = array(
						'goods_id' => $k,
						'use_storage' => 13,
						'admin_id' => $admin_id,
						'model_inventory' => $goods['model_attr'],
						'model_attr' => $goods['model_attr'],
						'warehouse_id' => $warehouse_id,
						'area_id' => $area_id,
						'suppliers_id' => $suppliers_id,
						'number' => '+ ' . $v,
						'batch_number' => $batch_number[$k],
						'remark' => $remark,
						'add_time' => gmtime()
					);	
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $new_log, 'INSERT');					
				}
				else
				{
					$result['error'][] = $goods_id;
				}
			}			
		}
	
		if($product_number)
		{
			foreach($product_number as $k=>$v){//$k goods_id  $v array('product_id'=>'val');
				//商品模式
				$warehouse_id = 0;
				$area_id = isset($area_ids[$k]) ? intval($area_ids[$k]) : 0;

				$where = " WHERE 1 ";
	
				/* 区分商品模式改变仓库库存 */
				$goods = get_goods_model($k);
				$warehouse_list = get_warehouse_list();

				if($region_id){
					$warehouse_id = $region_id;
					if(empty($area_id)){
						$sql = "SELECT rw.region_id FROM " .$GLOBALS['ecs']->table('region_warehouse'). " AS rw LEFT JOIN " .
						$GLOBALS['ecs']->table('warehouse_area_goods'). " AS wag ON rw.region_id = wag.region_id WHERE  rw.parent_id = '$region_id' AND rw.region_type = 1 AND wag.goods_id = '$k' ";
						$area_id = $GLOBALS['db']->getOne($sql, true);			
					}
				}
				if($goods['model_attr'] == 1){
					$table = "products_warehouse";
					$where .= " AND warehouse_id = '$warehouse_id'";
				}elseif($goods['model_attr'] == 2){
					$table = "products_area";
					$where .= " AND area_id = '$area_id' ";
				}else{
					$table = "products";
				}
				
				/* 库存入库 */
				foreach($v as $product_id => $val){
					$sql = " UPDATE ".$ecs->table($table). " SET ".
						   " product_number = product_number + '$val' ".
						   " $where AND product_id = '$product_id' ";

					if($db->query($sql)){
						//插入日志
						$new_log = array(
							'goods_id' => $k,
							'use_storage' => 13,
							'admin_id' => $admin_id,
							'model_inventory' => $goods['model_attr'],
							'model_attr' => $goods['model_attr'],
							'warehouse_id' => $warehouse_id,
							'area_id' => $area_id,
							'suppliers_id' => $suppliers_id,
							'number' => '+ ' . $val,
							'product_id' => $product_id,
							'batch_number' => $batch_number[$k],
							'remark' => $remark,
							'add_time' => gmtime()
						);	
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $new_log, 'INSERT');						
					}
					else
					{
						$result['error'][] = $product_id;
					}					
				}
			}
		}
	}
	/* 提示信息 */
	$links[] = array('href' => 'goods_psi.php?act=purchase', 'text' => $_LANG['back_purchase']);
	if($result['error'] == 0){
		sys_msg($_LANG['purchase_success'], 0, $links);
	}else{
		sys_msg($_LANG['purchase_failed'], 1, $links);
	}
	
}

/*------------------------------------------------------ */
//-- 库存管理（存）
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'inventory')
{
	admin_priv('goods_psi');
	$smarty->assign('menu_select', array('action' => '02_goods_storage', 'current' => '03_psi_inventory'));
	$smarty->assign('ur_here', $_LANG['03_psi_inventory']);
	$smarty->assign('action_link', array('text' => '导出EXCEL', 'href' => 'javascript:;'));
	$smarty->assign('full_page', 1);
	
    $list = get_psi_inventory($adminru['ru_id']);
    $smarty->assign('goods_list',   $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
	
    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);	
	
	assign_query_info();
	$smarty->display('psi_inventory.dwt');
}

/*------------------------------------------------------ */
//-- 库存管理查询（存）
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'inventory_query')
{
	check_authz_json('goods_psi');
    $list = get_psi_inventory($adminru['ru_id']);
    $smarty->assign('goods_list',   $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
	
    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);	
	
    make_json_result($smarty->fetch('psi_inventory.dwt'), '',
        array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/* ------------------------------------------------------ */
//-- 添加商品SKU/库存
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'add_sku') 
{
	check_authz_json('goods_psi');
    $json = new JSON;
    $result = array('error' => 0, 'message' => '', 'content' => '');
    
    $goods_id = !empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0;
    $user_id = !empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
    $warehouse_id = 0;
    $area_id = 0;
    
    $goods = get_goods_model($goods_id);
    
    $warehouse_list = get_warehouse_list();
    if($warehouse_list){
        $warehouse_id = $warehouse_list[0]['region_id'];
        $sql = "SELECT region_id FROM " .$ecs->table('region_warehouse'). " WHERE parent_id = '" .$warehouse_list[0]['region_id']. "'";
        $area_id = $db->getOne($sql, true);
    }
    
    $smarty->assign('warehouse_id', $warehouse_id);
    $smarty->assign('area_id', $area_id);
    
    $smarty->assign('goods', $goods);
    
    $smarty->assign('warehouse_list', $warehouse_list);
    $smarty->assign('goods_id', $goods_id);
    $smarty->assign('user_id', $user_id);
    
    $smarty->assign('goods_attr_price', $GLOBALS['_CFG']['goods_attr_price']);

    $product_list = get_goods_product_list($goods_id, $goods['model_attr'], $warehouse_id, $area_id);
    $smarty->assign('product_list',     $product_list['product_list']);
    $smarty->assign('sku_filter',       $product_list['filter']);
    $smarty->assign('sku_record_count', $product_list['record_count']);
    $smarty->assign('sku_page_count',   $product_list['page_count']);
    $smarty->assign('query',            $product_list['query']);
    $smarty->assign('full_page',    1);
	
	//是否设置属性 by wu start
	$goods_info = get_table_date('goods', "goods_id='$goods_id'", array('goods_name', 'goods_type', 'goods_number', 'bar_code', 'warn_number'));
	if($goods['model_attr'] == 2){
		$goods_table = "warehouse_area_goods";
		$region_id = $area_id;
	}elseif($goods['model_attr'] == 1){
		$goods_table = "warehouse_goods";
		$region_id = $warehouse_id;
	}else{
		$goods_table = "goods";
		$region_id = 0;
	}
	if(!empty($goods['model_attr'])){
		$region_info = get_table_date($goods_table, "goods_id='$goods_id' AND region_id='$region_id'", array('region_number'));
		$goods_info['goods_number'] = $region_info['region_number'];
	}	
	$smarty->assign('goods_info', $goods_info);
	//是否设置属性 by wu end	
	
	//判断是入库还是修改安全库存 by wu start
	$type = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);
	switch($type){
		case 'safe': $lbi = 'psi_goods_dialog_safe'; break;
		case 'in': $lbi = 'psi_goods_dialog_in'; break;
		default : $lbi = 'psi_goods_dialog_safe';
	}
	$smarty->assign('type', $type);
	//判断是入库还是修改安全库存 by wu end
	
	//补充数据 by wu start
	$suppliers_list = psi_suppliers_list();
	$smarty->assign('suppliers_list', $suppliers_list);
	//补充数据 by wu end
    
    $result['content'] = $GLOBALS['smarty']->fetch('library/'.$lbi.'.lbi');
    die($json->encode($result));
}

/* ------------------------------------------------------ */
//-- 添加商品SKU/库存
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'sku_query') 
{
	check_authz_json('goods_psi');
    $json = new JSON;
    $result = array('error' => 0, 'message' => '', 'content' => '');
    
    $smarty->assign('goods_attr_price', $GLOBALS['_CFG']['goods_attr_price']);	
    
    $product_list = get_goods_product_list();
    
    $smarty->assign('product_list',     $product_list['product_list']);
    $smarty->assign('sku_filter',       $product_list['filter']);
    $smarty->assign('sku_record_count', $product_list['record_count']);
    $smarty->assign('sku_page_count',   $product_list['page_count']);
    $smarty->assign('query',            $product_list['query']);
    
    $goods = array(
        'goods_id'          => $product_list['filter']['goods_id'],
        'model_attr'        => $product_list['filter']['model'],
        'warehouse_id'      => $product_list['filter']['warehouse_id'],
        'area_id'           => $product_list['filter']['area_id']
    );
    $smarty->assign('goods',            $goods);
	
	//是否设置属性 by wu start
	$goods_id = $goods['goods_id'];
	$goods_info = get_table_date('goods', "goods_id='$goods_id'", array('goods_name', 'goods_type', 'goods_number', 'bar_code', 'warn_number'));
	if($goods['model_attr'] == 2){
		$goods_table = "warehouse_area_goods";
		$region_id = $goods['area_id'];
	}elseif($goods['model_attr'] == 1){
		$goods_table = "warehouse_goods";
		$region_id = $goods['warehouse_id'];
	}else{
		$goods_table = "goods";
		$region_id = 0;
	}
	if(!empty($goods['model_attr'])){
		$region_info = get_table_date($goods_table, "goods_id='$goods_id' AND region_id='$region_id'", array('region_number'));
		$goods_info['goods_number'] = $region_info['region_number'];
	}	
	$smarty->assign('goods_info', $goods_info);
	//是否设置属性 by wu end	
	
	//判断是入库还是修改安全库存 by wu start
	$type = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);
	switch($type){
		case 'safe': $lbi = 'psi_goods_dialog_safe'; break;
		case 'in': $lbi = 'psi_goods_dialog_in'; break;
		default : $lbi = 'psi_goods_dialog_safe';
	}
	$smarty->assign('type', $type);
	//判断是入库还是修改安全库存 by wu end	
    
    make_json_result($smarty->fetch('library/'.$lbi.'.lbi'), '', array('pb_filter' => $product_list['filter'], 'pb_page_count' => $product_list['page_count'], 'class' => "attrlistDiv"));
}

/* ------------------------------------------------------ */
//-- 添加商品SKU/库存
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'save_inventory') 
{
	check_authz_json('goods_psi');
    $json = new JSON;
    $result = array('error' => 0, 'message' => '', 'content' => '');
	
	$goods_id = empty($_REQUEST['goods_id'])? 0:intval($_REQUEST['goods_id']);
	$goods_type = empty($_REQUEST['goods_type'])? 0:intval($_REQUEST['goods_type']);
	$goods_model = empty($_REQUEST['goods_model'])? 0:intval($_REQUEST['goods_model']);
	$warehouse_id = empty($_REQUEST['warehouse_id'])? 0:intval($_REQUEST['warehouse_id']);
	$area_id = empty($_REQUEST['area_id'])? 0:intval($_REQUEST['area_id']);
	$goods_number = empty($_REQUEST['goods_number'])? 0:intval($_REQUEST['goods_number']);
	$product_number = empty($_REQUEST['product_number'])? array():$_REQUEST['product_number'];
	$goods_batch_number = empty($_REQUEST['goods_batch_number'])? '':$_REQUEST['goods_batch_number'];
	$product_batch_number = empty($_REQUEST['product_batch_number'])? array():$_REQUEST['product_batch_number'];
	$suppliers_id = empty($_REQUEST['suppliers_id'])? 0:intval($_REQUEST['suppliers_id']);
	$remark = empty($_REQUEST['remark'])? '':trim($_REQUEST['remark']);
	
	if($goods_id > 0){
		//判断是地区还是仓库
		if($goods_model == 2){
			$region_id = $area_id;
			$table_goods = 'warehouse_area_goods';
			$table_products = 'products_area';
		}elseif($goods_model == 1){
			$region_id = $warehouse_id;
			$table_goods = 'warehouse_goods';
			$table_products = 'products_warehouse';
		}else{
			$region_id = 0;
			$table_goods = 'goods';
			$table_products = 'products';
		}
		
		//日志基础数据
		$new_log = array(
			'goods_id' => $goods_id,
			'use_storage' => 13,
			'admin_id' => $admin_id,
			'model_inventory' => $goods_model,
			'model_attr' => $goods_model,
			'warehouse_id' => $warehouse_id,
			'area_id' => $area_id,
			'suppliers_id' => $suppliers_id,
			'batch_number' => '',
			'remark' => $remark,
			'add_time' => gmtime()
		);
		
		//货品还是商品
		if(!empty($goods_type)){
			if($product_number){
				foreach($product_number as $key=>$val){
					$val = empty($val)? 0:intval($val);
					$batch_number = empty($product_batch_number[$key])? '':$product_batch_number[$key];
					if(!empty($val)){
						$sql = " UPDATE ".$GLOBALS['ecs']->table($table_products)." SET product_number = product_number + $val WHERE product_id = '$key' ";
						$GLOBALS['db']->query($sql);
						
						//插入日志
						$this_log = $new_log;
						$this_log['number'] = "+ " . $val;
						$this_log['product_id'] = $key;
						$this_log['batch_number'] = $batch_number;
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $this_log, 'INSERT');						
					}
				}
			}
		}else{
			if(empty($goods_model)){
				$sql = " UPDATE ".$GLOBALS['ecs']->table($table_goods)." SET goods_number = goods_number + $goods_number WHERE goods_id = '$goods_id' ";
			}else{
				$sql = " UPDATE ".$GLOBALS['ecs']->table($table_goods)." SET region_number = region_number + $goods_number WHERE goods_id = '$goods_id' AND region_id = '$region_id' ";
			}
			$GLOBALS['db']->query($sql);
			
			//插入日志
			$this_log = $new_log;
			$this_log['number'] = "+ " . $goods_number;
			$this_log['batch_number'] = $goods_batch_number;
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $this_log, 'INSERT');			
		}
	}		
	
	die($json->encode($result));
}

/*------------------------------------------------------ */
//-- 修改商品安全库存
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_warn_number') 
{
    check_authz_json('goods_psi');
    $goods_id = intval($_POST['id']);
    $warn_number = intval($_POST['val']);
    $goods_model = isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0;

    if($goods_model == 1){
        $table = "warehouse_goods";
    }elseif($goods_model == 2){
        $table = "warehouse_area_goods";
    }else{
        $table = "goods";
    }

    /* 修改商品安全库存 */
    $sql = "UPDATE " . $ecs->table('goods') . " SET warn_number = '$warn_number' WHERE goods_id = '$goods_id'";
    $result = $db->query($sql);

    if ($result) {
        clear_cache_files();
        make_json_result($warn_number);
    }
}

/*------------------------------------------------------ */
//-- 修改货品安全库存
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_product_warn_number') 
{
    check_authz_json('goods_psi');
    $product_id = intval($_POST['id']);
    $product_warn_number = intval($_POST['val']);
    $goods_model = isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0;

    if($goods_model == 1){
        $table = "products_warehouse";
    }elseif($goods_model == 2){
        $table = "products_area";
    }else{
        $table = "products";
    }

    /* 修改货品安全库存 */
    $sql = "UPDATE " . $ecs->table($table) . " SET product_warn_number = '$product_warn_number' WHERE product_id = '$product_id'";
    $result = $db->query($sql);

    if ($result) {
        clear_cache_files();
        make_json_result($product_warn_number);
    }
}

/*------------------------------------------------------ */
//-- 库存日志（存）
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'psi_log')
{
	admin_priv('goods_psi');
	$smarty->assign('ur_here', '库存日志');
	$smarty->assign('action_link', array('text' => '导出EXCEL', 'href' => 'javascript:;'));
	$smarty->assign('full_page', 1);
	
	$goods_id = empty($_REQUEST['goods_id'])? 0:intval($_REQUEST['goods_id']);
    $list = get_psi_log();
    $smarty->assign('goods_list',   $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
	
    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);	
	
	assign_query_info();
	$smarty->display('psi_log.dwt');
}

/*------------------------------------------------------ */
//-- 库存日志查询（存）
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'psi_log_query')
{
	check_authz_json('goods_psi');
	$goods_id = empty($_REQUEST['goods_id'])? 0:intval($_REQUEST['goods_id']);
    $list = get_psi_log();
    $smarty->assign('goods_list',   $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
	
    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);	
	
    make_json_result($smarty->fetch('psi_log.dwt'), '',
        array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 修改批次
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_psi_log')
{
    check_authz_json('goods_psi');
    $id       = intval($_POST['id']);
    $number    = intval($_POST['val']);
	
	if(!empty($number)){
		//处理之前先查询原数据(如果在修改时商品模式或者属性发生变化则根据具体情况进行修改)
		$psi_log = get_table_date('goods_inventory_logs', "id='$id'", array('*'));
		//判断是地区还是仓库
		if(!empty($psi_log['area_id'])){
			$region_id = $psi_log['area_id'];
			$table_goods = 'warehouse_area_goods';
			$table_products = 'products_area';
		}elseif(!empty($psi_log['warehouse_id'])){
			$region_id = $psi_log['warehouse_id'];
			$table_goods = 'warehouse_goods';
			$table_products = 'products_warehouse';
		}else{
			$region_id = 0;
			$table_goods = 'goods';
			$table_products = 'products';
		}
		//货品还是商品
		if(!empty($psi_log['product_id'])){
			$sql = " UPDATE ".$GLOBALS['ecs']->table($table_products)." SET product_number = product_number + $number WHERE product_id = '{$psi_log['product_id']}' ";
		}else{
			if(empty($region_id)){
				$sql = " UPDATE ".$GLOBALS['ecs']->table($table_goods)." SET goods_number = goods_number + $number WHERE goods_id = '{$psi_log['goods_id']}' ";
			}else{
				$sql = " UPDATE ".$GLOBALS['ecs']->table($table_goods)." SET region_number = region_number + $number WHERE goods_id = '{$psi_log['goods_id']}' AND region_id = '$region_id' ";
			}
		}
		$GLOBALS['db']->query($sql);
		
		//插入日志
		$new_log = array();
		$new_log['id'] = NULL;
		$new_log['admin_id'] = $admin_id;
		$new_log['add_time'] = gmtime();
		if($number > 0){
			$new_log['use_storage'] = 13;
			$new_log['number'] = "+ " . $number;
		}elseif($number < 0){
			$new_log['use_storage'] = 8;
			$new_log['number'] = "- " . -$number;
		}else{
			
		}
		$insert_log = array_merge($psi_log, $new_log);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $insert_log, 'INSERT');
		//$id = $GLOBALS['db']->insert_id();
	}
	
	clear_cache_files();
	make_json_result($number);
}

/*--------------------------------------------------------*/
//商品模块弹窗
/*--------------------------------------------------------*/
elseif ($_REQUEST['act'] == 'goods_info')
{
	check_authz_json('goods_psi');
	$json = new JSON;
	$result = array('content' => '','mode'=>'');
        /*处理数组*/
        $_REQUEST['spec_attr']=strip_tags(urldecode($_REQUEST['spec_attr']));
        $_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
        $_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
        if(!empty($_REQUEST['spec_attr'])){
            $spec_attr = $json->decode(stripslashes($_REQUEST['spec_attr']));
            $spec_attr = sec_object_to_array($spec_attr);
        }
        $spec_attr['is_title'] = isset($spec_attr['is_title']) ? $spec_attr['is_title'] : 0;
        $spec_attr['itemsLayout'] = isset($spec_attr['itemsLayout']) ? $spec_attr['itemsLayout'] : 'row4';
        $result['mode'] = isset($_REQUEST['mode'])  ? addslashes($_REQUEST['mode'])  : '';
		$result['diff'] = isset($_REQUEST['diff'])  ?  intval($_REQUEST['diff'])  : 0;
        $lift = isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '';
        //取得商品列表
        if($spec_attr['goods_ids'])
        {
            $goods_info = explode(',', $spec_attr['goods_ids']);
            foreach( $goods_info as $k=>$v)
            {  
                if( !$v )
                {
                    unset( $goods_info[$k] );  
                }
            }   
            if(!empty($goods_info))
           {
               $where = " WHERE g.is_on_sale=1 AND g.is_delete=0 AND g.goods_id".db_create_in($goods_info);
               
               //ecmoban模板堂 --zhuo start
                if($GLOBALS['_CFG']['review_goods'] == 1){
                        $where .= ' AND g.review_status > 2 ';
                }
                //ecmoban模板堂 --zhuo end
    
               $sql = "SELECT g.goods_name,g.goods_id,g.goods_thumb,g.original_img,g.shop_price FROM " . $ecs->table('goods') . " AS g " . $where ;
               $goods_list = $db->getAll($sql);
               
               foreach($goods_list as $k=>$v){
                    $goods_list[$k]['shop_price'] = price_format($v['shop_price']);
                }
        
               $smarty->assign('goods_list', $goods_list);
               $smarty->assign('goods_count',     count($goods_list));
           }
        }
        /* 取得分类列表 */
        //获取下拉列表 by wu start
        set_default_filter(0, $cat_id); //设置默认筛选
        $smarty->assign('parent_category', get_every_category($cat_id)); //上级分类导航
        $smarty->assign('select_category_html', $select_category_html);
        $smarty->assign('brand_list',   get_brand_list());
        $smarty->assign('arr',   $spec_attr);
        $smarty->assign("mode",$result['mode']);
        $smarty->assign("lift",$lift);
	$result['content'] = $GLOBALS['smarty']->fetch('library/select_purchase_goods.lbi');
	die($json->encode($result));
}

/*------------------------------------------------------ */
//-- 商品模块
/*------------------------------------------------------ */
elseif($_REQUEST['act'] == 'changedgoods')
{
	check_authz_json('goods_psi');
    require(ROOT_PATH . '/includes/lib_goods.php');
    $json = new JSON;
    $result = array('error' => 0, 'message' => '', 'content' => '');
    $spec_attr = array();
    $result['lift'] = isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '';
    $result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
    if($_REQUEST['spec_attr']){
        $_REQUEST['spec_attr']=strip_tags(urldecode($_REQUEST['spec_attr']));
        $_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
        if(!empty($_REQUEST['spec_attr'])){
            $spec_attr = $json->decode($_REQUEST['spec_attr']);
            $spec_attr = object_to_array($spec_attr);
        }
    }
    $sort_order = isset($_REQUEST['sort_order'])  ? $_REQUEST['sort_order']  :  1;
    $cat_id = isset($_REQUEST['cat_id'])  ? explode('_', $_REQUEST['cat_id'])  :  array();
    $brand_id = isset($_REQUEST['brand_id'])  ? intval($_REQUEST['brand_id'])  : 0;
    $keyword = isset($_REQUEST['keyword'])  ? addslashes($_REQUEST['keyword'])  : '';
    $goodsAttr = isset($spec_attr['goods_ids'])  ? explode(',', $spec_attr['goods_ids'])  :  '';
    $goods_ids = isset($_REQUEST['goods_ids'])  ? explode(',', $_REQUEST['goods_ids'])  :  '';
    $result['goods_ids'] = !empty($goodsAttr) ? $goodsAttr : $goods_ids;
    $result['cat_desc'] = isset($spec_attr['cat_desc'])  ? addslashes($spec_attr['cat_desc'])  :  '';
    $result['cat_name'] = isset($spec_attr['cat_name'])  ? addslashes($spec_attr['cat_name'])  :  '';
    $result['align'] = isset($spec_attr['align'])  ? addslashes($spec_attr['align'])  :  '';
    $result['is_title'] = isset($spec_attr['is_title'])  ? intval($spec_attr['is_title'])  : 0;
    $result['itemsLayout'] = isset($spec_attr['itemsLayout'])  ? addslashes($spec_attr['itemsLayout'])  : '';
	$result['diff'] = isset($_REQUEST['diff'])  ?  intval($_REQUEST['diff'])  : 0;
    $type = isset($_REQUEST['type'])  ? intval($_REQUEST['type'])  :  0;
    $temp = isset($_REQUEST['temp'])   ?  $_REQUEST['temp'] : 'goods_list';
    $resetRrl = isset($_REQUEST['resetRrl']) ? intval($_REQUEST['resetRrl']) : 0;
    $result['mode'] = isset($_REQUEST['mode'])  ? $_REQUEST['mode']  : '';
    $smarty->assign('temp',$temp);
    $where = "WHERE g.is_on_sale=1 AND g.is_delete=0 ";
    //ecmoban模板堂 --zhuo start
    if($GLOBALS['_CFG']['review_goods'] == 1){
            $where .= ' AND g.review_status > 2 ';
    }
    //ecmoban模板堂 --zhuo end
    if($cat_id[0] > 0)
    {
        $where .= " AND ".get_children($cat_id[0]);
    }
    if($brand_id > 0)
    {
        $where .= " AND g.brand_id = '$brand_id'";
    }
    if($keyword)
    {
        $where .= " AND g.goods_name  LIKE '%$keyword%'";
    }
     if($result['goods_ids'] && $type == '0')
    {
        $where .= " AND g.goods_id".db_create_in($result['goods_ids']);
    }
    $sort = '';
    switch ($sort_order) {
            case '1':
                $sort .= " ORDER BY g.add_time ASC";
                break;

            case '2' :
                $sort .= " ORDER BY g.add_time DESC";
                break;

            case '3' :
                $sort .= " ORDER BY g.sort_order ASC";
                break;

            case '4' :
                $sort .= " ORDER BY g.sort_order DESC";
                break;

            case '5' :
                $sort .= " ORDER BY g.goods_name ASC";
                break;

        case '6' :
            $sort .= " ORDER BY g.goods_name DESC";
            break;
    }
    if($type == 1){
        $list = getGoodslist($where,$sort);
        $goods_list = $list['list'];
        $filter = $list['filter'];
        $filter['cat_id'] = $cat_id[0];
        $filter['sort_order'] = $sort_order;
        $filter['keyword'] = $keyword;
        $smarty->assign('filter',     $filter);
        
    }else{
        $sql = "SELECT g.promote_start_date, g.promote_end_date, g.promote_price, g.goods_name, g.goods_id, g.goods_thumb, g.shop_price, g.market_price, g.original_img, g.model_attr FROM " . 
            $ecs->table('goods') . " AS g " . $where .$sort;
        $goods_list = $db->getAll($sql);
    }
    if (!empty($goods_list)) {
        foreach ($goods_list as $k => $v) {
            $goods_list[$k]['goods_thumb'] = get_image_path($v['goods_id'], $v['goods_thumb']);
            $goods_list[$k]['original_img'] = get_image_path($v['goods_id'], $v['original_img']);
            $goods_list[$k]['url'] = build_uri('goods', array('gid' => $v['goods_id']), $v['goods_name']);
            $goods_list[$k]['shop_price'] = price_format($v['shop_price']);
            if ($v['promote_price'] > 0) {
                $goods_list[$k]['promote_price'] = bargain_price($v['promote_price'], $v['promote_start_date'], $v['promote_end_date']);
            } else {
                $goods_list[$k]['promote_price'] = 0;
            }
            if ($v['goods_id'] > 0 && in_array($v['goods_id'], $result['goods_ids']) && !empty($result['goods_ids'])) {
                $goods_list[$k]['is_selected'] = 1;
            }
        }
    }

    $smarty->assign("is_title",$result['is_title']);
    $smarty->assign('goods_list', $goods_list);

    $smarty->assign('goods_count',     count($goods_list));
	$smarty->assign('attr',$spec_attr);
    $result['content'] = $GLOBALS['smarty']->fetch('library/psi_goods_list.lbi');
    die(json_encode($result));
}

/* ------------------------------------------------------ */
//-- 添加入库商品
/* ------------------------------------------------------ */ 
elseif ($_REQUEST['act'] == 'add_purchase_goods')
{
	check_authz_json('goods_psi');
	$goods_ids = empty($_REQUEST['goods_ids']) || $_REQUEST['goods_ids'] == 'undefined' ? '' : explode(',', $_REQUEST['goods_ids']);
	$selected  = empty($_REQUEST['selected'])  ? '' : explode(',', $_REQUEST['selected']);
	$result    = array('error' => 0, 'message' => '', 'content' => '');	
	
	if($selected){
		if($goods_ids){
			$goods_ids_arr = array_unique(array_merge($goods_ids,$selected));	
			$goods_ids = implode(',', $goods_ids_arr);			
		}else{
			$goods_ids = implode(',', $selected);
		}

	}else{
		$goods_ids = implode(',', $goods_ids);
	}
	if($goods_ids){
		$list = get_select_purchase_goods($goods_ids);
		$smarty->assign('purchase_goods',    $list);
		$smarty->assign('goods_ids', $goods_ids);
		$result['content'] = $smarty->fetch('templates/psi_purchase.dwt');
		die(json_encode($result));	
	}
}

/**
 *  供应商列表
 */
function psi_suppliers_list()
{
        /* 查询 */
	$sql = "SELECT suppliers_id, suppliers_name, suppliers_desc, is_check
			FROM " . $GLOBALS['ecs']->table("suppliers") ;
    $res = $GLOBALS['db']->getAll($sql);

    return $res;
}

/*对象转数组*/
function sec_object_to_array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    if($_arr){
        foreach ($_arr as $key => $val) {
            $val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
            $arr[$key] = $val;
        }
    }else{
        $arr = array();
    }
    
    return $arr;
}

function getGoodslist($where = '',$sort = '',$search = '',$leftjoin = ''){
    
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('goods') ." AS g ".$leftjoin. $where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter = page_and_size($filter);
        $where .= $sort." LIMIT " . $filter['start'].",".$filter['page_size'];
        $sql = "SELECT g.promote_start_date, g.promote_end_date, g.promote_price, g.goods_name, g.goods_id, g.goods_thumb, g.shop_price, g.market_price, g.original_img ,g.model_price $search FROM " . 
            $GLOBALS['ecs']->table('goods') . " AS g " .$leftjoin. $where ;
        
        $goods_list = $GLOBALS['db']->getAll($sql);
        $filter['page_arr'] = seller_page($filter,$filter['page']);
        return array('list' => $goods_list, 'filter' => $filter);
}

/*获取商品列表*/
function get_psi_inventory($ru_id = 0){
    /* 过滤查询 */
    $filter = array();
	
    //ecmoban模板堂 --zhuo start
    $filter['keyword'] = !empty($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
    }
    //ecmoban模板堂 --zhuo end
    
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'goods_id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = 'WHERE 1 ';
    
    /* 关键字 */
    if (!empty($filter['keyword']))
    {
        $where .= " AND (goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%')";  
    }

    //ecmoban模板堂 --zhuo start
    if($ru_id > 0){
        $where .= " and user_id = '$ru_id'";
    }
    //ecmoban模板堂 --zhuo end

    /* 获得总记录数据 */
    $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('goods'). $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 获得商品数据 */
    $arr = array();
    $sql = 'SELECT * '.
            'FROM ' .$GLOBALS['ecs']->table('goods').
			$where.
            'ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $idx = 0;
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
		//判断模式
		if($rows['model_inventory'] == 2){
			$table_goods = 'warehouse_area_goods';
			$table_products = 'products_area';
		}elseif($rows['model_inventory'] == 1){
			$table_goods = 'warehouse_goods';
			$table_products = 'products_warehouse';
		}else{
			$table_goods = 'goods';
			$table_products = 'products';
		}
		//其他数据		
        $rows['user_name'] = get_shop_name($rows['user_id'], 1);
        $rows['goods_thumb'] = get_image_path($rows['goods_id'], $rows['goods_thumb'], true);
		//处理货品
		$rows['goods_take_number'] = 0;
		$rows['total_number'] = 0;
		if(!empty($rows['goods_type'])){
			//货品总数
			$sql = " SELECT SUM(product_number) as total_number FROM ".$GLOBALS['ecs']->table($table_products)." WHERE goods_id = '{$rows['goods_id']}' ";
			$total_number = $GLOBALS['db']->getOne($sql);
			$rows['total_number'] = empty($total_number)? 0:$total_number;
		}else{
			//商品总数
			if(empty($rows['model_inventory'])){
				$rows['total_number'] = $rows['goods_number'];
			}else{
				$sql = " SELECT SUM(region_number) FROM ".$GLOBALS['ecs']->table($table_goods)." WHERE goods_id = '{$rows['goods_id']}' ";
				$rows['total_number'] = $GLOBALS['db']->getOne($sql);
			}	
		}
		 
        $arr[$idx] = $rows;
        $idx++;
    }

    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);	
}

/*获取库存日志*/
function get_psi_log(){
    /* 过滤查询 */
    $filter = array();
	
    //ecmoban模板堂 --zhuo start
    $filter['keyword'] = !empty($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
    }
    //ecmoban模板堂 --zhuo end
	$filter['goods_id'] = empty($_REQUEST['goods_id'])? 0:intval($_REQUEST['goods_id']);
    
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'gil.id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = 'WHERE 1 ';
    
    /* 关键字 */
    if (!empty($filter['keyword']))
    {
        $where .= " AND (gil.batch_number LIKE '%" . mysql_like_quote($filter['keyword']) . "%')";  
    }

    if(!empty($filter['goods_id'])){
        $where .= " and gil.goods_id = '$filter[goods_id]'";
    }

    /* 获得总记录数据 */
    $sql = 'SELECT COUNT(*) '.
			'FROM ' .$GLOBALS['ecs']->table('goods_inventory_logs')." AS gil ".
			'LEFT JOIN '.$GLOBALS['ecs']->table('goods')." AS g ON g.goods_id = gil.goods_id ".
			$where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 获得记录数据 */
    $arr = array();
    $sql = 'SELECT gil.*, g.goods_name, g.goods_type, g.goods_number '.
            'FROM ' .$GLOBALS['ecs']->table('goods_inventory_logs')." AS gil ".
			'LEFT JOIN '.$GLOBALS['ecs']->table('goods')." AS g ON g.goods_id = gil.goods_id ".
			$where.
            'ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $idx = 0;
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
		//判断模式
		if(!empty($rows['area_id'])){
			$region_id = $rows['area_id'];
			$table_goods = 'warehouse_area_goods';
			$table_products = 'products_area';
		}elseif(!empty($rows['warehouse_id'])){
			$region_id = $rows['warehouse_id'];
			$table_goods = 'warehouse_goods';
			$table_products = 'products_warehouse';
		}else{
			$region_id = 0;
			$table_goods = 'goods';
			$table_products = 'products';
		}		
		//商品总数
		$rows['total_number'] = 0;
		if(!empty($rows['product_id'])){
			//货品数据
			$product_info = get_table_date($table_products, "product_id='{$rows['product_id']}'", array('product_number', 'goods_attr'));
			if(!empty($product_info)){
				$rows['total_number'] = $product_info['product_number'];
				$goods_attr = explode('|', $product_info['goods_attr']);
				$sql = " SELECT attr_value FROM ".$GLOBALS['ecs']->table('goods_attr')." WHERE goods_attr_id IN (".implode(',', $goods_attr).") ";
				$rows['goods_attr'] = $GLOBALS['db']->getCol($sql);
			}		
		}else{
			//商品总数
			if(empty($region_id)){
				$rows['total_number'] = $rows['goods_number'];
			}else{
				$sql = " SELECT region_number FROM ".$GLOBALS['ecs']->table($table_goods)." WHERE goods_id = '{$rows['goods_id']}' AND region_id = '$region_id' ";
				$rows['total_number'] = $GLOBALS['db']->getOne($sql);
			}
		}		
		//仓库名称
		$rows['warehouse_name'] = get_table_date('region_warehouse', "region_id='{$rows['warehouse_id']}'", array('region_name'), 2);
		//地区名称
		$rows['area_name'] = get_table_date('region_warehouse', "region_id='{$rows['area_id']}'", array('region_name'), 2);
		//供货商名称
		$rows['suppliers_name'] = get_table_date('suppliers', "suppliers_id='{$rows['suppliers_id']}'", array('suppliers_name'), 2);
		
        $arr[$idx] = $rows;
        $idx++;
    }

    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);	
}

/**
 * 获取选中的入库商品
 *
 * @access  public
 * @param   integer $goods_id
* @return  array
 */
function get_select_purchase_goods($goods_ids = '')
{
	$region_id  = empty($_REQUEST['region_id']) ? 0 : intval($_REQUEST['region_id']);
	$area_id 	= empty($_REQUEST['area_id']) 	? 0 : intval($_REQUEST['area_id']);
	
	$where = " where 1 ";
	if(!empty($goods_ids)){
		$where .= " AND goods_id IN ($goods_ids) ";		
	}
	
	/* 获活动数据 */
	$sql = " SELECT goods_id, goods_name, goods_sn, goods_number, goods_thumb FROM ". $GLOBALS['ecs']->table('goods')  .$where ;

    $row = $GLOBALS['db']->getAll($sql);

    foreach ($row AS $key => $val)
    {
		$goods = get_goods_model($val['goods_id']);
		if($region_id && $goods['model_attr']){
			if($goods['model_attr'] == 1){//仓库模式
				$num = $GLOBALS['db']->getOne(" SELECT region_number FROM ". $GLOBALS['ecs']->table('warehouse_goods') ." WHERE region_id = '$region_id' AND goods_id = '".$val['goods_id']."' ");
				$row[$key]['goods_number'] = $num;				
			}elseif($goods['model_attr'] == 2){//地区模式
				if(empty($area_id)){
					$sql = "SELECT rw.region_id FROM " .$GLOBALS['ecs']->table('region_warehouse'). " AS rw LEFT JOIN " .
					$GLOBALS['ecs']->table('warehouse_area_goods'). " AS wag ON rw.region_id = wag.region_id WHERE  rw.parent_id = '$region_id' AND rw.region_type = 1 AND wag.goods_id = '".$val['goods_id']."' ";
					$area_id = $GLOBALS['db']->getOne($sql, true);			
				}	
				$num = $GLOBALS['db']->getOne(" SELECT region_number FROM ". $GLOBALS['ecs']->table('warehouse_area_goods') ." WHERE region_id = '$area_id' AND goods_id = '".$val['goods_id']."' ");
				$row[$key]['goods_number'] = $num;		
				
				$sql = " SELECT rw.region_id, rw.region_name FROM ".$GLOBALS['ecs']->table('region_warehouse')." AS rw , ".$GLOBALS['ecs']->table('warehouse_area_goods')." AS wag 
						WHERE rw.parent_id = '$region_id' AND rw.region_type = 1 AND rw.region_id = wag.region_id AND wag.goods_id = '".$val['goods_id']."' ORDER BY rw.region_id ASC ";
				$area_list = $GLOBALS['db']->getAll($sql);
				
				if($area_list){
					$row[$key]['area_list'] = $area_list;
					$row[$key]['select_area'] = true;	
					$row[$key]['area_id']	= $area_id;
				}
			}
		}

		$row[$key]['goods_thumb'] = get_image_path($val['goods_id'], $val['goods_thumb'], true);
		$row[$key]['product_list'] = get_goods_product_list($val['goods_id'], $goods['model_attr'], $region_id, $area_id, false);// false 不分页
		//货品行数
		$rowspan = count($row[$key]['product_list']);
		$row[$key]['rowspan'] = empty($rowspan)? 1:$rowspan;
    }
	
    return $row;		
}

?>