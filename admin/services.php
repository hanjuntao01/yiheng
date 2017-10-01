<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$_REQUEST['act']  =  !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'index';

if ($_REQUEST['act'] == 'list')
{
    $services = services_list();

    $smarty->assign('services_list',        $services['list']);
    $smarty->assign('filter',          $services['filter']);
    $smarty->assign('record_count',    $services['record_count']);
    $smarty->assign('page_count',      $services['page_count']);
    $smarty->assign('extension_code',      $services['extension_code']);
    $smarty->assign('removed',      ($services['filter']['extension_code'] == 'deleted') ? 1 : 0);

    if($_GET['is_ajax'] === '1'){
        make_json_result($smarty->fetch('services_list.dwt'), '',
            array('filter' => $services['filter'], 'page_count' => $services['page_count']));
        exit;
    }
    $smarty->assign('ur_here', '客服列表'); // 当前导航
    $smarty->assign('full_page', 1);

    /** 接待统计 */
    $times['times'] = statistics_reception();
    $times['today_times'] = statistics_reception(1);
    $times['people'] = statistics_reception_customer();
    $times['today_people'] = statistics_reception_customer(1);
    $smarty->assign('times', $times); //

    $smarty->display('services_list.dwt');
}
//已删除客服列表
elseif ($_REQUEST['act'] == 'removed' || $_REQUEST['act'] == 'backservice'){
    if($_REQUEST['act'] == 'backservice'){
        //恢复客服
        $id = empty($_GET['id']) ? 0 : strip_tags($_GET['id']);
        back_to_service($id);

    }
    $url = 'services.php?act=list&extension_code=deleted';

    ecs_header("Location: $url\n");
}

//添加客服页面
elseif ($_REQUEST['act'] == 'add')
{
    $smarty->assign('ur_here', '客服管理'); // 当前导航

    $admins = admin_list();

    $smarty->assign('admin_list', $admins); //
    $smarty->assign('form_action', 'insert'); //

    $smarty->display('services_add.dwt');
}
//添加、更新客服功能
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    $services_name = empty($_POST['services_name']) ? 0 : strip_tags($_POST['services_name']);
    $services_desc = empty($_POST['services_desc']) ? 0 : strip_tags($_POST['services_desc']);
    $services = empty($_POST['services']) ? 0 : intval($_POST['services']);

    if(empty($services_name)){
        sys_msg('请填写昵称', 1);
    }elseif(empty($services_desc)){
        sys_msg('请填写描述', 1);
    }elseif(empty($services)){
        sys_msg('请选择管理员', 1);
    }

    $sql = "SELECT user_name FROM  ". $ecs->table('admin_user') . " WHERE user_id=" . $services;

    $userName = $db->getOne($sql);
    if(!$userName){
        sys_msg('没有该管理员', 1);
    }

    if($_REQUEST['act'] == 'insert'){
        $sql = "SELECT user_name, status FROM  ". $ecs->table('im_service') . " WHERE user_id=" . $services;

        $res = $db->getRow($sql);
        if($res['status'] == 1){
            sys_msg('该管理员已是客服', 1);
        }elseif($res['status'] === 0 || $res['status'] === '0'){
            $sql = "UPDATE".$ecs->table('im_service')." SET nick_name='{$services_name}', post_desc='{$services_desc}', status=1  WHERE user_id=" . $services;
            $res = $db->query($sql);
        }else{
            $sql = "INSERT INTO ".$ecs->table('im_service')."(user_id, user_name, nick_name, post_desc, chat_status, status) ".
                "VALUES ($services, '$userName', '$services_name', ".
                "'$services_desc', '0', '1')";
            $res = $db->query($sql);
        }
        if(!$res){
            sys_msg('添加客服失败', 1);
        }
    }elseif($_REQUEST['act'] == 'update'){
        $id = empty($_POST['id']) ? 0 : intval($_POST['id']);

        $sql = "UPDATE".$ecs->table('im_service')." SET user_id=$services, nick_name='{$services_name}', post_desc='{$services_desc}'  WHERE id=" . $id;
        $res = $db->query($sql);
        if(!$res){
            sys_msg('更新客服失败', 1);
        }
    }
    admin_log('', 'service', 'insert'); // 记录日志

    $url = 'services.php?act=list';
    ecs_header("Location: $url\n");
}
//删除客服
elseif ($_REQUEST['act'] == 'remove'){
    $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
    $isAjax = empty($_GET['is_ajax']) ? 0 : intval($_GET['is_ajax']);
    if(!$isAjax){
        make_json_error("invalid method");
    }
    if(!$id){
        make_json_error("invalid params");
    }
    $sql = "SELECT user_name, chat_status FROM  ". $ecs->table('im_service') . " WHERE id = " . $id . " AND status = 1";

    $res = $db->getRow($sql);
    if(!$res){
        make_json_error("客服不存在");
    }

    //删除操作
    $sql = "UPDATE". $ecs->table('im_service') . " SET status = 0  WHERE id = " . $id;
    $res = $db->query($sql);
    if(!$res){
        make_json_error("客服不存在");
    }

    $services = services_list();
    $smarty->assign('services_list', $services['list']); //
    $smarty->assign('services_list',        $services['list']);
    $smarty->assign('filter',          $services['filter']);
    $smarty->assign('record_count',    $services['record_count']);
    $smarty->assign('page_count',      $services['page_count']);
    make_json_result($smarty->fetch('services_list.dwt'), "删除成功");

}
/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_record_selected']);
    }
    else
    {
        /* 检查权限 */
        admin_priv('services');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['remove']))
        {
            /* 删除记录 */
            $sql = "UPDATE". $ecs->table('im_service') . " SET status = 0  WHERE id " . db_create_in($ids);
            $res = $db->query($sql);

            /* 记日志 */
            admin_log('', 'service_batch_remove', 'service');

            /* 清除缓存 */
            clear_cache_files();
            $link[] = array('text' => '返回', 'href' => 'services.php?act=list');
            sys_msg($_LANG['batch_drop_ok'],'',$link);
        }
    }
}
/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit'){
    $id = empty($_GET['id']) ? 0 : intval($_GET['id']);

    $sql = "SELECT user_id, nick_name, post_desc FROM " . $ecs->table('im_service') . " WHERE id = " . $id;
    $customer = $db->getRow($sql);

    $admins = admin_list($id);
    $smarty->assign('form_action', 'update'); //
    $smarty->assign('id', $id); //
    $smarty->assign('admin_list', $admins); //
    $smarty->assign('customer', $customer); //
    $smarty->display('services_add.dwt');
}elseif ($_REQUEST['act'] == 'dialog_list'){
    //会话记录
    $id = empty($_GET['id']) ? 0 : intval($_GET['id']);

    $list = dialog_list($id, 0);

    $smarty->assign('id', $id); //
    $smarty->assign('dialog_list', $list); //
    $smarty->assign('full_page', 1);

    $smarty->display('services_dialog_list.dwt');
}elseif ($_REQUEST['act'] == 'dialog_list_ajax'){
    //异步获取会话列表
    $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
    $val = empty($_POST['val']) ? 0 : intval($_POST['val']);

    $list = dialog_list($id, $val);

    $smarty->assign('dialog_list', $list); //
    make_json_result($smarty->fetch('services_dialog_list.dwt'));
}elseif ($_REQUEST['act'] == 'message_list_ajax'){
    //消息列表
    $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
    $customer_id = empty($_POST['customer_id']) ? 0 : intval($_POST['customer_id']);
    $service_id = empty($_POST['service_id']) ? 0 : intval($_POST['service_id']);
    $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
    $keyword = empty($_POST['keyword']) ? 0 : strip_tags(trim($_POST['keyword']));

    $dialog = dialog($id);
    $message = message_list($customer_id, $service_id, $page, $keyword);
    $list = $message['list'];
    $count = $message['count'];
    $smarty->assign('message_page', 1); //
    $smarty->assign('dialog', $dialog); //
    $smarty->assign('message_list', $list); //
    make_json_result($smarty->fetch('services_dialog_list.dwt'), $count);
}

/** 生成word */
elseif($_REQUEST['act'] == 'generage_word'){
    $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
    $customer_id = empty($_GET['customer_id']) ? 0 : intval($_GET['customer_id']);
    $service_id = empty($_GET['service_id']) ? 0 : intval($_GET['service_id']);

    $message = message_list($customer_id, $service_id, -1);
    $list = $message['list'];
    $dialog = dialog($id);

    //生成消息
    require (dirname(__FILE__) . '/../mobile/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');

    $excel = new PHPExcel();
    $letter = array('A','B','C','D','E','F','F','G');

    foreach($list as $k => $v){
        $excel->getActiveSheet()->setCellValue("$letter[0]$k",strip_tags($v['message']));
        $excel->getActiveSheet()->setCellValue("$letter[1]$k",$v['add_time']);

        if($v['user_type'] == 1){
            $excel->getActiveSheet()->setCellValue("$letter[2]$k",$dialog['user_name']);
        }elseif($v['user_type'] == 2){
            $excel->getActiveSheet()->setCellValue("$letter[2]$k",$dialog['nick_name']);
        }
    }
    $write = new PHPExcel_Writer_Excel5($excel);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename="'.$dialog[user_name].'.xls"');
    header("Content-Transfer-Encoding:binary");
    $write->save('php://output');
}
/*--------------------------------------------------*/
//--根据日期找出记录
/*--------------------------------------------------*/
elseif ($_REQUEST['act'] == 'get_message_by_date'){
    //消息列表
    $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
    $customer_id = empty($_POST['customer_id']) ? 0 : intval($_POST['customer_id']);
    $service_id = empty($_POST['service_id']) ? 0 : intval($_POST['service_id']);
    $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
    $date = empty($_POST['date']) ? 0 : strip_tags(trim($_POST['date']));
    $dialog = dialog($id);
    $smarty->assign('dialog', $dialog); //

    $message = message_list($customer_id, $service_id, $page, '', strtotime($date));
    $list = $message['list'];
    $count = $message['count'];
    $smarty->assign('message_page', 1); //
    $smarty->assign('message_list', $list); //
    make_json_result($smarty->fetch('services_dialog_list.dwt'), $count);
}

/** 客服列表 */
function services_list(){
    $filter = array();
    $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
    $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
    $filter['extension_code']   = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
    $filter['keyword']   = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);

    /** 获得总记录数据 */
    $sqlCount  = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('im_service') . ' s'
        . " LEFT JOIN " . $GLOBALS['ecs']->table('admin_user') . ' a on a.user_id = s.user_id'
        . " LEFT JOIN " . $GLOBALS['ecs']->table('seller_shopinfo') . ' r on r.ru_id = a.ru_id'
        .' WHERE s.id <> 0 ';
    $sql  = 'SELECT s.*, r.shop_name FROM ' . $GLOBALS['ecs']->table('im_service') . ' s'
        . " LEFT JOIN " . $GLOBALS['ecs']->table('admin_user') . ' a on a.user_id = s.user_id'
        . " LEFT JOIN " . $GLOBALS['ecs']->table('seller_shopinfo') . ' r on r.ru_id = a.ru_id'
        .' WHERE s.id <> 0 ';

    //按客服区分搜索
    if($filter['extension_code'] == 'platform'){
        $sql .= ' AND a.ru_id = 0 AND s.status = 1';
        $sqlCount .= ' AND a.ru_id = 0 AND s.status = 1';
    }elseif($filter['extension_code'] == 'seller'){
        $sql .= ' AND a.ru_id <> 0 AND s.status = 1';
        $sqlCount .= ' AND a.ru_id <> 0 AND s.status = 1';
    }elseif($filter['extension_code'] == 'deleted'){
        $sql .= ' AND s.status = 0';
        $sqlCount .= ' AND s.status = 0';
    }else{
        $sql .= ' AND s.status = 1';
        $sqlCount .= ' AND s.status = 1';
    }
    //关键词搜索
    if($filter['keyword'] != ''){
        $sql .= ' AND (r.shop_name like "%' . $filter['keyword'] . '%" OR s.nick_name like "%' . $filter['keyword'] . '%")';
        $sqlCount .= ' AND (r.shop_name like "%' . $filter['keyword'] . '%" OR s.nick_name like "%' . $filter['keyword'] . '%")';
    }

    $filter['record_count'] = $GLOBALS['db']->getOne($sqlCount);

    $filter = page_and_size($filter);

    $list = array();

    $sql  .= ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

    $res  = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['chat_status'] = ($rows['chat_status'] == 0) ? '已登录' : '未登录';
        $rows['avatar'] = '../data/images_user/' . (empty($rows['avatar']) ? '/no_picture.jpg' : $rows['avatar']);
        $list[] = $rows;
    }
    return array('list' => $list, 'filter' => $filter, 'page_count' =>  $filter['page_count'], 'record_count' => $filter['record_count']);
}

/** 管理员列表 */
function admin_list($id = 0){
    //查找商家
    if($id === 0){
        //后台管理员
        $sql = "SELECT a.ru_id, s.id FROM " . $GLOBALS['ecs']->table("admin_user") . ' a'
            . " LEFT JOIN " . $GLOBALS['ecs']->table("im_service") . " s ON s.user_id = a.user_id"
            . " WHERE a.user_id = " . $_SESSION['admin_id'];
        $row = $GLOBALS['db']->getRow($sql);
        $ruId = (int)$row['ru_id'];
        $id = (int)$row['id'];

    }else{
        //编辑商家客服
        $sql = "SELECT a.ru_id FROM " . $GLOBALS['ecs']->table("im_service") . " s"
            . " LEFT JOIN " . $GLOBALS['ecs']->table("admin_user") . " a ON s.user_id = a.user_id"
            . " WHERE s.id = " . $id;
        $ruId = $GLOBALS['db']->getOne($sql);
    }

    //end

    $sql = "SELECT user_id, user_name FROM " . $GLOBALS['ecs']->table("admin_user") . " WHERE ru_id = " . $ruId . " AND user_id not in (SELECT user_id FROM " . $GLOBALS['ecs']->table("im_service") . ' WHERE status=1 AND id <> ' . $id . ")";

    $list = $GLOBALS['db']->getAll($sql);

    return $list;
}

/** 接待人次统计 */
function statistics_reception($now = false){
    $sql = "SELECT count(id) FROM " . $GLOBALS['ecs']->table("im_dialog");

    $nowTime = strtotime(date('Y-m-d', time()));
    if($now){
        $sql .= ' where start_time > ' . $nowTime;
    }

    $times = $GLOBALS['db']->getOne($sql);
    return $times;
}

/** 接待人数统计 */
function statistics_reception_customer ($now = false){
    $sql = "SELECT COUNT(DISTINCT customer_id) FROM " . $GLOBALS['ecs']->table("im_dialog") ;

    $nowTime = strtotime(date('Y-m-d', time()));
    if($now){
        $sql .= ' where start_time > ' . $nowTime;
    }

    $times = $GLOBALS['db']->getOne($sql);
    return $times;
}

/**
 * 会话记录
 * 以客户为单位
 */
function dialog_list ($id, $val = 0){
    $sql = "SELECT id,  customer_id, goods_id, store_id, start_time, end_time FROM " . $GLOBALS['ecs']->table("im_dialog") . " WHERE services_id = {$id}";

    if($val === 0){
        $time = strtotime(date('Y-m-d', time()));
        $sql .= " AND start_time > " . $time;
    }elseif($val === 1){
        $time = strtotime('-1 week');
        $sql .= " AND start_time > " . $time;
    }elseif($val === 2){
        $time = strtotime('-1 month');
        $sql .= " AND start_time > " . $time;
    }
    $sql .= ' ORDER BY start_time DESC';
    $res = $GLOBALS['db']->getAll($sql);

    $temp = [];
    foreach($res as $k => $v){
        if(in_array($v['customer_id'], $temp)){
            unset($res[$k]);
            continue;
        }
        $temp[] = $v['customer_id'];
    }

    foreach($res as $k => $v){
        if($v['goods_id'] > 0){
            $sql = "SELECT goods_name, goods_thumb FROM " . $GLOBALS['ecs']->table("goods") . " WHERE goods_id = " . $v['goods_id'];
            $goods = $GLOBALS['db']->getRow($sql);
            $res[$k]['goods_name'] = $goods['goods_name'];
            $res[$k]['goods_thumb'] = format_goods_pic($goods['goods_thumb']);

        }

        $sql = "SELECT user_name FROM " . $GLOBALS['ecs']->table("users") . " WHERE user_id = " . $v['customer_id'];
        $res[$k]['user_name'] = $GLOBALS['db']->getOne($sql);

        $res[$k]['start_time'] = date('Y-m-d H:i:s', $v['start_time']);
        $res[$k]['end_time'] = date('Y-m-d H:i:s', $v['end_time']);
    }

    return $res;
}
/** 会话信息 */
function dialog($id){
    $sql = "SELECT u.user_name, s.nick_name, FROM_UNIXTIME(start_time) AS start_time FROM " . $GLOBALS['ecs']->table("im_dialog") . " d"
            . " LEFT JOIN " . $GLOBALS['ecs']->table("im_service") . " s ON d.services_id = s.id"
            . " LEFT JOIN " . $GLOBALS['ecs']->table("users") . " u ON d.customer_id = u.user_id"
            . " WHERE d.id = " . $id;

    $res = $GLOBALS['db']->getRow($sql);
    return $res;
}

/** 消息列表 */
function message_list($customer_id, $service_id, $page=0, $keyword='', $date = ''){
    $size = 10;
    $start = ($page-1) * $size;
    $start = (intval($start) < 0) ? 0 : (int)$start;

    $sql = "SELECT message, user_type, FROM_UNIXTIME(add_time) AS add_time FROM " . $GLOBALS['ecs']->table("im_message") . " WHERE ((from_user_id = " . $customer_id . " AND to_user_id = " . $service_id . ") OR (from_user_id = " . $service_id . " AND to_user_id = " . $customer_id . ")";
    $sqlCount = "SELECT count(id) FROM " . $GLOBALS['ecs']->table("im_message") . " WHERE ((from_user_id = " . $customer_id . " AND to_user_id = " . $service_id . ") OR (from_user_id = " . $service_id . " AND to_user_id = " . $customer_id . ")";

    if(!empty($keyword)){
        //关键词
        $sql .= ") AND (message like '%{$keyword}%') ORDER BY add_time";
        $sqlCount .= ") AND (message like '%{$keyword}%') ORDER BY add_time";
    }elseif(!empty($date)){
        //日期
        $sql .= ") AND UNIX_TIMESTAMP(FROM_UNIXTIME(add_time, '%Y-%m-%d')) = $date";
        $sqlCount .= ") AND UNIX_TIMESTAMP(FROM_UNIXTIME(add_time, '%Y-%m-%d')) = $date";
    }elseif(!empty($page)){
        $sql .= ") ORDER BY add_time";
        $sqlCount .= ")";
    }else{
        $sql .= ") ORDER BY add_time";
        $sqlCount .= ")";
    }
    if($page != -1){
        $sql .= " limit $start, $size";
    }

    $res = $GLOBALS['db']->getAll($sql);
    $count = $GLOBALS['db']->getOne($sqlCount);

    foreach ($res as $k => $v) {
        $res[$k]['message'] = htmlspecialchars_decode($v['message']);
    }

    return array('list' => $res, 'count' => ceil($count / $size));
}

/** 已删除客服列表 */
function removed_services_list(){
    $filter = array();
    $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
    $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    /** 获得总记录数据 */
    $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('im_service'). ' WHERE status = 0';
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);
    $list = array();
    $sql  = 'SELECT * FROM ' .$GLOBALS['ecs']->table('im_service')
        .' WHERE status = 0 ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
    $res  = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['chat_status'] = ($rows['chat_status'] == 1) ? '登录中' : '未登录';
        $list[] = $rows;
    }

    return array('list' => $list, 'filter' => $filter, 'page_count' =>  $filter['page_count'], 'record_count' => $filter['record_count']);
}
/** 恢复客服 */
function back_to_service($id){
    $sql = "UPDATE " . $GLOBALS['ecs']->table("im_service") . " SET  status = 1 WHERE status = 0 AND id = " . $id;

    $GLOBALS['db']->query($sql);
}

/** 格式化商品图片 */
function format_goods_pic($path){
    $path  = '../' . $path;

    return $path;
}