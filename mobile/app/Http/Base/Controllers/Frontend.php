<?php

namespace App\Http\Base\Controllers;

use App\Libraries\Shop;
use App\Libraries\Mysql;
use App\Libraries\Error;
use Think\Hook;

abstract class Frontend extends Foundation
{
    public $province_id = 0;
    public $city_id = 0;
    public $district_id = 0;
    public $caching = false;
    public $custom = '';
    public $customs = '';

    public function __construct()
    {
        parent::__construct();
        $this->start();
        //ecjia验证登录
        $this->ecjia_login();
    }

    /**
     * ecjia验证登录
     * &origin=app&openid=openid&token=token
     */
    private function ecjia_login()
    {
        if (isset($_GET['origin']) && $_GET['origin'] == 'app') {
            $openid = I('get.openid');
            $token = I('get.token');
            $sql = "select cu.access_token,u.user_name from {pre}connect_user as cu LEFT JOIN {pre}users as u on cu.user_id = u.user_id where open_id = '$openid' ";
            $user = $this->db->getRow($sql);
            if ($token == $user['access_token']) {
                /* 设置成登录状态 */
                $GLOBALS['user']->set_session($user['user_name']);
                $GLOBALS['user']->set_cookie($user['user_name']);
                update_user_info();
                recalculate_price();
            }
        }
    }

    private function start()
    {
        $this->init();
        $this->init_user();
        $this->init_gzip();
        $this->init_assign();
        $this->init_area();
        $ru_id = get_ru_id();
        if ($ru_id > 0) {
            $wechat = '\\App\\Http\\Wechat\\Controllers\\Index';
            $wechat::snsapi_base($ru_id);
        } else {
            $this->init_oauth();
        }
        Hook::listen('frontend_init');
        $this->assign('lang', array_change_key_case(L()));
        $this->assign('charset', CHARSET);
    }

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    private function init()
    {
        // 加载helper文件
        $helper_list = ['time', 'base', 'common', 'main', 'insert', 'goods', 'wechat'];
        $this->load_helper($helper_list);
        // 全局对象
        $this->ecs = $GLOBALS['ecs'] = new Shop(C('DB_NAME'), C('DB_PREFIX'));
        $this->db = $GLOBALS['db'] = new Mysql();
        $this->err = $GLOBALS['err'] = new Error('message');
        // 全局配置
        $GLOBALS['_CFG'] = load_ecsconfig();
        $GLOBALS['_CFG']['template'] = 'default';
        if ($GLOBALS['_CFG']['rewrite'] > 0) {
            C('URL_MODEL', 2);
        }
        $GLOBALS['_CFG']['rewrite'] = 0;
        C('shop', $GLOBALS['_CFG']);
        // 应用配置
        $app_config = MODULE_BASE_PATH . 'config/web.php';
        C('app', file_exists($app_config) ? require $app_config : []);
        // 全局语言包
        L(require(LANG_PATH . C('shop.lang') . '/common.php'));
        // 应用模块语言包
        $app_lang = MODULE_BASE_PATH . 'Language/' . C('shop.lang') . '/' . strtolower(MODULE_NAME) . '.php';
        L(file_exists($app_lang) ? require $app_lang : []);
        // 控制器语言包
        $app_lang = MODULE_BASE_PATH . 'Language/' . C('shop.lang') . '/' . strtolower(CONTROLLER_NAME) . '.php';
        L(file_exists($app_lang) ? require $app_lang : []);
        // 应用helper文件
        $this->load_helper('function', 'app');
        // 商店关闭了，输出关闭的消息
        if (C('shop.shop_closed') == 1) {
            exit('<p>' . L('shop_closed') . '</p><p>' . C('close_comment') . '</p>');
        }
        if (C('shop.wap_config') == 0) {
            exit('<p>' . L('wap_config') . '</p><p>' . C('close_comment') . '</p>');
        }
        // 定义session_id
        if (!defined('INIT_NO_USERS')) {
            session(array('name' => 'ECS_ID'));
            session('[start]');
            define('SESS_ID', session_id());
        }
        //加载商创helper文件
        $helper_list = ['ecmoban', 'function'];
        $this->load_helper($helper_list);
    }

    private function init_user()
    {
        if (!defined('INIT_NO_USERS')) {
            // 会员信息
            $GLOBALS['user'] = $this->users = init_users();
            if (!isset($_SESSION['user_id'])) {
                /* 获取投放站点的名称 */
                $site_name = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes(L('self_site'));
                $from_ad = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;

                $wechat_from = array('timeline', 'groupmessage', 'singlemessage');//如果在微信分享链接，referer为touch
                if (in_array($site_name, $wechat_from)) {
                    $site_name = addslashes(L('self_site'));
                }
                $_SESSION['from_ad'] = $from_ad; // 用户点击的广告ID
                $_SESSION['referer'] = stripslashes($site_name); // 用户来源

                unset($site_name);

                if (!defined('INGORE_VISIT_STATS')) {
                    visit_stats();
                }
            }

            if (empty($_SESSION['user_id'])) {
                if ($this->users->get_cookie()) {
                    /* 如果会员已经登录并且还没有获得会员的帐户余额、积分以及优惠券 */
                    if ($_SESSION['user_id'] > 0) {
                        update_user_info();
                    }
                } else {
                    $_SESSION['user_id'] = 0;
                    $_SESSION['user_name'] = '';
                    $_SESSION['email'] = '';
                    $_SESSION['user_rank'] = 0;
                    $_SESSION['discount'] = 1.00;
                    if (!isset($_SESSION['login_fail'])) {
                        $_SESSION['login_fail'] = 0;
                    }
                }
            }

            // 设置推荐会员
            if (isset($_GET['u'])) {
                set_affiliate();
            }

            // 设置推荐分销商ID
            if (isset($_GET['d'])) {
                set_drp_affiliate();
            }

            // 设置商家ID cookie
            $ru_id = isset($_GET['ru_id']) ? intval($_GET['ru_id']) : 0;
            set_ru_id($ru_id);

            // session 不存在，检查cookie
            if (!empty($_COOKIE['ECS']['user_id']) && !empty($_COOKIE['ECS']['password'])) {
                // 找到了cookie, 验证cookie信息
                $condition = array(
                    'user_id' => intval($_COOKIE['ECS']['user_id']),
                    'password' => $_COOKIE['ECS']['password']
                );
                $row = $this->db->table('users')->where($condition)->find();

                if (!$row) {
                    $time = time() - 3600;
                    cookie('ECS[user_id]', '');
                    cookie('ECS[password]', '');
                } else {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    update_user_info();
                }
            }

            if (isset($this->tpl)) {
                $this->tpl->assign('ecs_session', $_SESSION);
            }
        }
    }

    //映射公用模板的值
    private function init_assign()
    {
        //热搜
        $search_keywords = C('shop.search_keywords');
        $hot_keywords = array();
        if ($search_keywords) {
            $hot_keywords = explode(',', $search_keywords);
        }
        $this->assign('hot_keywords', $hot_keywords);
        // 浏览关键词记录
        $history = '';
        if (!empty($_COOKIE['ECS']['keywords'])) {
            $history = explode(',', $_COOKIE['ECS']['keywords']);
            $history = array_unique($history);  //移除数组中的重复的值，并返回结果数组。
        }
        $this->assign('history_keywords', $history);

        // WXJS-SDK  微信浏览器内访问并安装了微信通
        $is_wechat = (is_wechat_browser() && is_dir(APP_WECHAT_PATH)) ? 1 : 0;
        $this->assign('is_wechat', $is_wechat);
        if ($is_wechat == 1) {
            $share_data = $this->get_wechat_share_content();
            $this->assign('share_data', $share_data);
        }
    }

    /**
     * 地区选择
     */
    public function init_area()
    {
        //判断地区关联是否选择完毕 start
        $city_district_list = get_isHas_area($_COOKIE['type_city']);
        if (!$city_district_list) {
            cookie('type_district', 0);
            $_COOKIE['type_district'] = 0;
        }

        $provinceT_list = get_isHas_area($_COOKIE['type_province']);
        $cityT_list = get_isHas_area($_COOKIE['type_city'], 1);
        $districtT_list = get_isHas_area($_COOKIE['type_district'], 1);

        if ($_COOKIE['type_province'] > 0 && $provinceT_list) {
            if ($city_district_list) {
                if ($cityT_list['parent_id'] == $_COOKIE['type_province'] && $_COOKIE['type_city'] == $districtT_list['parent_id']) {
                    $_COOKIE['province'] = $_COOKIE['type_province'];
                    if ($_COOKIE['type_city'] > 0) {
                        $_COOKIE['city'] = $_COOKIE['type_city'];
                    }

                    if ($_COOKIE['type_district'] > 0) {
                        $_COOKIE['district'] = $_COOKIE['type_district'];
                    }
                }
            } else {
                if ($cityT_list['parent_id'] == $_COOKIE['type_province']) {
                    $_COOKIE['province'] = $_COOKIE['type_province'];
                    if ($_COOKIE['type_city'] > 0) {
                        $_COOKIE['city'] = $_COOKIE['type_city'];
                    }

                    if ($_COOKIE['type_district'] > 0) {
                        $_COOKIE['district'] = $_COOKIE['type_district'];
                    }
                }
            }
        }
        //判断地区关联是否选择完毕 end
        $this->province_id = isset($_COOKIE['province']) ? $_COOKIE['province'] : 0;
        $this->city_id = isset($_COOKIE['city']) ? $_COOKIE['city'] : 0;
        $this->district_id = isset($_COOKIE['district']) ? $_COOKIE['district'] : 0;

        //判断仓库是否存在该地区
        $warehouse_date = array('region_id', 'region_name');
        $warehouse_where = "regionId = '$this->province_id'";
        $warehouse_province = get_table_date('region_warehouse', $warehouse_where, $warehouse_date);

        $sellerInfo = get_seller_info_area();
        if (!$warehouse_province) {
            $this->province_id = $sellerInfo['province'];
            $this->city_id = $sellerInfo['city'];
            $this->district_id = $sellerInfo['district'];
        }

        cookie('province', $this->province_id);
        cookie('city', $this->city_id);
        cookie('district', $this->district_id);
    }

    //判断是否支持 Gzip 模式
    private function init_gzip()
    {
        if (!defined('INIT_NO_SMARTY') && gzip_enabled()) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }
    }

    /**
     * 自动授权跳转
     */
    private function init_oauth()
    {
        if (is_wechat_browser() && empty($_SESSION['unionid']) && strtolower(MODULE_NAME) != 'oauth') {
            $sql = "SELECT `auth_config` FROM" . $GLOBALS['ecs']->table('touch_auth') . " WHERE `type` = 'wechat' AND `status` = 1";
            $auth_config = $GLOBALS['db']->getOne($sql);
            if ($auth_config) {
                $res = unserialize($auth_config);
                $config = array();
                foreach ($res as $key => $value) {
                    $config[$value['name']] = $value['value'];
                }
                $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __HOST__ . $_SERVER['REQUEST_URI'];
                $this->redirect('oauth/index/index', array('type' => 'wechat', 'back_url' => urlencode($back_url)));
            }
        }
    }

    /**
     * 取当前页面地址
     * 如果用户登录 当前地址则需要加上此用户的uid,用于分享出去的地址（非显示在浏览器中的地址）
     * @return string
     */
    public function get_current_url()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        $u = I('get.u', 0, 'intval');
        // 如果含u参数 并且不相同，取u参数 替换为登录用户u参数
        if (!empty($u) && !empty($_SESSION['user_id']) && $u != $_SESSION['user_id']) {
            $uri = url_set_value($uri, 'u', $_SESSION['user_id']);
        }
        return __HOST__ . $uri;
    }

    /**
     * 微信JSSDK分享内容
     * Example: $share_data = array(
     *     'title' => '', //分享标题 默认商店名称
     *     'desc' => '', //分享描述 默认商店描述
     *     'link' => '', //分享链接 默认当前页面链接 含参数
     *     'img' => '', //分享图片 注意需要绝对路径 http://www.abc.com/mobile/public/img/wxsdk.png
     *     );
     * @param array $share_data 分享数据
     * @return
     */
    public function get_wechat_share_content($share_data = array())
    {
        if (!empty($share_data['img'])) {
            $share_img = (strtolower(substr($share_data['img'], 0, 4)) == 'http') ? $share_data['img'] : __HOST__ . $share_data['img'];
        } else {
            $share_img = elixir('img/wxsdk.png', true);
        }
        $data = array(
            'title' => !empty($share_data['title']) ? $share_data['title'] : C('shop.shop_name'),
            'desc' => !empty($share_data['desc']) ? str_replace(array(" ", "　", "\t", "\n", "\r"), '', html_in($share_data['desc'])) : C('shop.shop_desc'),
            'link' => !empty($share_data['link']) ? $share_data['link'] : $this->get_current_url(),
            'img' => $share_img,
        );
        return $data;
    }
}
