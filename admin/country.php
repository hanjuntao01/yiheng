<?php

/**
 * ECSHOP 管理中心品牌管理
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: brand.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table("brand"), $db, 'brand_id', 'brand_name');

/*------------------------------------------------------ */
//-- 品牌列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      '国家列表页');
    $smarty->assign('action_link',  array('text' => '添加国家', 'href' => 'country.php?act=add'));
    $smarty->assign('full_page',    1);

    $country_list = get_countrylist();

    $smarty->assign('country_list',   $country_list['country']);
    $smarty->assign('filter',       $country_list['filter']);
    $smarty->assign('record_count', $country_list['record_count']);
    $smarty->assign('page_count',   $country_list['page_count']);

    assign_query_info();
    $smarty->display('country_list.dwt');
}

/*------------------------------------------------------ */
//-- 添加品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('country_list');

    $smarty->assign('ur_here',     "添加国家");
    $smarty->assign('action_link', array('text' => "国家列表", 'href' => 'country.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->assign('country_list', array('sort_order'=>50, 'is_show'=>1));
    $smarty->display('country_info.dwt');
}
elseif ($_REQUEST['act'] == 'insert')
{
    /*检查品牌名是否重复*/
    admin_priv('country_list');
	$c_name = empty($_POST['c_name'])? "" : trim($_POST['c_name']);
	//判断国名是不是重复
	$sql="SELECT id FROM ".$GLOBALS['ecs']->table('country')." WHERE c_name = '$c_name'";
	$res=$GLOBALS['db']->getAll($sql);
		
    if (!empty($res))
    {
        sys_msg(sprintf('国名重复', stripslashes('国名')), 1);
    }

     /*处理图片*/
    $img_name = basename($image->upload_image($_FILES['c_img'],'c_img'));
    
    get_oss_add_file(array(DATA_DIR . '/c_img/' . $img_name));

    /*插入数据*/

    $sql = "INSERT INTO ".$ecs->table('country')."(c_name, c_img) ".
           "VALUES ('{$c_name}','{$img_name}')";
    $db->query($sql);

    admin_log('国名','add','country');

    /* 清除缓存 */
    clear_cache_files();

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'country.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'country.php?act=list';

    sys_msg('国家添加成功', 0, $link);
}

/*------------------------------------------------------ */
//-- 删除品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('country_list');

    $id = intval($_GET['id']);
    
    get_del_batch('', $id, array('c_img'), 'id', 'country', 0, DATA_DIR . '/c_img/'); //删除图片
    
    //$exc->drop($id);
	$sql="DELETE FROM ".$GLOBALS['ecs']->table('country')." WHERE id='$id'";
	$GLOBALS['db']->query($sql);

    /* 更新商品的品牌编号 */
/*    $sql = "UPDATE " .$ecs->table('goods'). " SET brand_id=0 WHERE brand_id='$id'";
    $db->query($sql);
*/
    $url = 'country.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $country_list = get_countrylist();
    $smarty->assign('brand_list',   $country_list['country']);
    $smarty->assign('filter',       $country_list['filter']);
    $smarty->assign('record_count', $country_list['record_count']);
    $smarty->assign('page_count',   $country_list['page_count']);

    make_json_result($smarty->fetch('country_list.htm'), '',
        array('filter' => $country_list['filter'], 'page_count' => $country_list['page_count']));
}
/*------------------------------------------------------ */
//-- 编辑品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('country_list');
    $sql = "SELECT * ".
            "FROM " .$ecs->table('country'). " WHERE id='$_REQUEST[id]'";
    $country = $db->GetRow($sql);
	
	$country['c_img'] = empty($country['c_img']) ? '' : "../" . DATA_DIR . '/c_img/'.$country['c_img']; //by wu

    $smarty->assign('ur_here',     '编辑国家');
    $smarty->assign('action_link', array('text' => $_LANG['53_country'], 'href' => 'country.php?act=list&' . list_link_postfix()));
    $smarty->assign('country',       $country);
    $smarty->assign('form_action', 'updata');

    assign_query_info();
    $smarty->display('country_info.dwt');
}

/**
 * 获取品牌列表
 *
 * @access  public
 * @return  array
 */
function get_countrylist()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 分页大小 */
        $filter = array();

        $filter['sort_by']          = 'id';
        $filter['sort_order']       = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        /* 记录总数以及页数 */
        if (isset($_POST['c_name']))
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('brand') .' WHERE c_name = \''.$_POST['c_name'].'\'';
        }
        else
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('brand');
        }

        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);
		
        /* 查询记录 */
        if (isset($_POST['c_name']))
        {
            if(strtoupper(EC_CHARSET) == 'GBK')
            {
                $keyword = iconv("UTF-8", "gb2312", $_POST['c_name']);
            }
            else
            {
                $keyword = $_POST['c_name'];
            }
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('country')." WHERE c_name like '%{$keyword}%' ORDER BY $filter[sort_by] $filter[sort_order]";
        }
        else
        {
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('country')." where 1 ORDER BY $filter[sort_by] $filter[sort_order]";
        }

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $country_logo = empty($rows['c_img']) ? '' :
            '<a href="../' . DATA_DIR . '/c_img/'.$rows['c_img'].'" target="_brank"><img src="images/picflag.gif" width="16" height="16" border="0" alt="国旗图片" /></a>';

        $rows['c_img'] = $country_logo;

        $arr[] = $rows;
    }

    return array('country' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>
