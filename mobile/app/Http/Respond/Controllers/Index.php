<?php

namespace App\Http\Respond\Controllers;

use App\Http\Base\Controllers\Frontend;

class Index extends Frontend
{
    private $data = array();

    public function __construct()
    {
        parent::__construct();
        // 获取参数
        $this->data = array(
            'code' => I('get.code')
        );
        if (isset($_GET['code'])) {
            unset($_GET['code']);
        }
    }

    /**
     * 处理支付同步通知
     */
    public function actionIndex()
    {
        // 提示类型
        $msg_type = 2;
        $payment = $this->getPayment();
        if ($payment === false) {
            $msg = L('pay_disabled');
        } else {
            if ($payment->callback($this->data)) {
                $msg = L('pay_success');
                $msg_type = 0;
            } else {
                $msg = L('pay_fail');
                $msg_type = 1;
            }
        }
        // 显示页面
        $this->assign('message', $msg);
        $this->assign('msg_type', $msg_type);
        $this->assign('page_title', L('pay_status'));
        $this->display();
    }

    /**
     * 处理支付异步通知
     */
    public function actionNotify()
    {
        $payment = $this->getPayment();
        if ($payment === false) {
            exit('plugin load fail');
        }
        $payment->notify($this->data);
    }

    /**
     * 获得支付信息
     */
    private function getPayment()
    {
        /* 判断启用状态 */
        $condition = array(
            'pay_code' => $this->data['code'],
            'enabled' => 1
        );
        $enabled = $this->db->table('payment')->where($condition)->count();
        $plugin = ADDONS_PATH . 'payment/' . $this->data['code'] . '.php';
        if (!is_file($plugin) || $enabled == 0) {
            return false;
        }
        /* 实例化插件 */
        require_cache($plugin);
        $payment = new $this->data['code']();
        return $payment;
    }
}
