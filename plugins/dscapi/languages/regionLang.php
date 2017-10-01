<?php

/**
 * DSC 地区言语包
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Zhuo $
 * $Id: regionLang.php 2016-01-04 Zhuo $
 */

namespace languages;

class regionLang {

    static private $lang_update_conf;
    static private $lang_insert_conf;

    /**
     * 构造函数
     *
     * @access  static public
     * @param
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * 获取地区数据言语包
     *
     * @access  static public
     * @return  bool
     */
    static public function lang_region_request() {
        self::$lang_insert_conf = array(
            'msg_success' => array(
                'success' => '成功获取数据',
                'error' => 0
            ),
            'msg_failure' => array(
                'failure' => '数据为空值',
                'error' => 1
            ),
            'where_failure' => array(
                'failure' => '条件为空',
                'error' => 2,
            )
        );

        return self::$lang_insert_conf;
    }

    /**
     * 地区数据插入言语包
     *
     * @access  static public
     * @return  bool
     */
    static public function lang_region_insert() {

        self::$lang_insert_conf = array(
            'msg_success' => array(
                'success' => '数据提交成功',
                'error' => 0
            ),
            'msg_failure' => array(
                'failure' => '数据提交失败',
                'error' => 1
            )
        );

        return self::$lang_insert_conf;
    }

    /**
     * 地区数据更新言语包
     *
     * @access  static public
     * @return  bool
     */
    static public function lang_region_update() {

        self::$lang_update_conf = array(
            'msg_success' => array(
                'success' => '数据更新成功',
                'error' => 0,
            ),
            'msg_failure' => array(
                'failure' => '数据为空',
                'error' => 1,
            ),
            'where_failure' => array(
                'failure' => '条件为空',
                'error' => 2,
            ),
            'null_failure' => array(
                 'failure' => '数据不存在',
                'error' => 4,
            )
        );

        return self::$lang_update_conf;
    }

    /**
     * 地区数据更新言语包
     *
     * @access  static public
     * @return  bool
     */
    static public function lang_region_delete() {

        self::$lang_update_conf = array(          
            'msg_success' => array(
                'success' => '删除成功',
                'error' => 0,
            ),
            'msg_failure' => array(
                'failure' => '删除失败',
                'error' => 1,
            ),
            'where_failure' => array(
                'failure' => '条件为空',
                'error' => 2,
            )
        );

        return self::$lang_update_conf;
    }
	
	static public function __callStatic($method, $arguments) {
        return call_user_func_array(array(self,$method), $arguments);
    }

}
