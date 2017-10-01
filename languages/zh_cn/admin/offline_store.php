<?php

/**
 * ECSHOP 广告管理语言文件
 * ============================================================================
 * * 版权所有 2005-2017 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: ads.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['add_stores']                  = '添加门店';
$_LANG['stores_user']                 = '登陆名';
$_LANG['stores_pwd']                  = '登陆密码';
$_LANG['confirm_pwd']                 = '确认密码';
$_LANG['stores_name']                 = '门店名称';
$_LANG['stores_name_dsc']             = '请认真填写您的门店名称，以确保用户（购买者）线下到店自提时查找。';
$_LANG['area_info']                   = '所在地区';
$_LANG['area_info_dsc']               = '所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。';
$_LANG['stores_address']              = '详细地址';
$_LANG['stores_address_dsc']          = '请认真填写详细地址，以确保用户（购物者）线下到店自提时能最准确的到达您的门店。';
$_LANG['stores_tel']                  = '联系电话';
$_LANG['stores_tel_dsc']              = '请认真填写门店联系电话，方便用户（购物者）通过该电话与您直接取得联系。';
$_LANG['stores_email']                = 'email';
$_LANG['stores_email_dsc']            = '请认真填写门店email，方便密码找回。';
$_LANG['stores_opening_hours']        = '营业时间';
$_LANG['stores_opening_hours_dsc']    = '如实填写您的线下门店营业时间，以免用户（购物者）在营业时间外到店产生误会。';
$_LANG['stores_traffic_line']         = '交通线路';
$_LANG['stores_traffic_line_dsc']     = '如您的门店周围有公交、地铁线路到达，请填写该选项，多条线路请以“|”进行分隔。';
$_LANG['stores_img']                  = '实景图片';
$_LANG['stores_img_dsc']              = '将您的实体店面沿街图上传，方便用户（购物者）线下到店自提时能最准确直观的找到您的门店。';
$_LANG['is_confirm']                  = '状态';
$_LANG['title_exist']                 = '门店名称已存在';
$_LANG['invalid_file']                = '上传文件格式不正确';
$_LANG['only_stores_name']            = '登陆名已存在';
$_LANG['is_different']                = '两次密码不一致';
$_LANG['GO_add']                      = '继续添加';
$_LANG['bank_list']                   = '返回列表';
$_LANG['add_succeed']                 = '添加成功';
$_LANG['delete_succeed']              = '删除成功';
$_LANG['delete_fail']                 = '请选择删除项';
$_LANG['file_url']                    = '或输入文件地址';
$_LANG['newpass']                     = '新登录密码';
$_LANG['edit_succeed']                = '编辑成功';
$_LANG['open_batch']                  = '批量开启';
$_LANG['off_batch']                   = '批量关闭';
$_LANG['drop_batch']                  = '批量删除';
$_LANG['handle_succeed']              = '操作成功';
$_LANG['back_list']                   = '返回列表';
$_LANG['shop_name']                   = '商家名称';
$_LANG['overall_sum']                 = '有效订单金额';
$_LANG['query']                       = '查询';
$_LANG['sale_stat']                   = '销量统计';
$_LANG['start_end_date'] 			  = '起止时间';

/* 订单搜索 */
$_LANG['order_sn'] = '订单号';
$_LANG['consignee'] = '收货人';
$_LANG['all_status'] = '订单状态';

$_LANG['cs'][OS_UNCONFIRMED] = '待确认';
$_LANG['cs'][CS_AWAIT_PAY] = '待付款';
$_LANG['cs'][CS_AWAIT_SHIP] = '待发货';
$_LANG['cs'][CS_FINISHED] = '已完成';
$_LANG['cs'][PS_PAYING] = '付款中';
$_LANG['cs'][OS_CANCELED] = '取消';
$_LANG['cs'][OS_INVALID] = '无效';
$_LANG['cs'][OS_RETURNED] = '退货';
$_LANG['cs'][OS_SHIPPED_PART] = '部分发货';

/* 订单状态 */
$_LANG['os'][OS_UNCONFIRMED] = '未确认';
$_LANG['os'][OS_CONFIRMED] = '已确认';
$_LANG['os'][OS_CANCELED] = '<font color="red"> 取消</font>';
$_LANG['os'][OS_INVALID] = '<font color="red">无效</font>';
$_LANG['os'][OS_RETURNED] = '<font color="red">退货</font>';
$_LANG['os'][OS_SPLITED] = '已分单';
$_LANG['os'][OS_SPLITING_PART] = '部分分单';

$_LANG['ss'][SS_UNSHIPPED] = '未发货';
$_LANG['ss'][SS_PREPARING] = '配货中';
$_LANG['ss'][SS_SHIPPED] = '已发货';
$_LANG['ss'][SS_RECEIVED] = '收货确认';
$_LANG['ss'][SS_SHIPPED_PART] = '已发货(部分商品)';
$_LANG['ss'][SS_SHIPPED_ING] = '发货中';

$_LANG['ps'][PS_UNPAYED] = '未付款';
$_LANG['ps'][PS_PAYING] = '付款中';
$_LANG['ps'][PS_PAYED] = '已付款';

/*js语言项*/
$_LANG['js_languages']['stores_user_null']    = '登陆名不能为空';
$_LANG['js_languages']['stores_pwd_null']     = '登陆密码不能为空';
$_LANG['js_languages']['confirm_pwd_null']    = '确认密码不能为空';
$_LANG['js_languages']['stores_name_null']    = '门店名称不能为空';
$_LANG['js_languages']['country_null']        = '国家不能为空';
$_LANG['js_languages']['province_null']       = '省份不能为空';
$_LANG['js_languages']['city_null']           = '城市不能为空';
$_LANG['js_languages']['district_null']       = '地区不能为空';
$_LANG['js_languages']['stores_address_null'] = '详细地址不能为空';
$_LANG['js_languages']['stores_tel_null']     = '联系电话不能为空';
$_LANG['js_languages']['stores_opening_hours_null']  = '营业时间不能为空';
$_LANG['js_languages']['stores_img_null']     = '实景图片不能为空';
$_LANG['js_languages']['email_null']     = 'email不能为空';
?>
