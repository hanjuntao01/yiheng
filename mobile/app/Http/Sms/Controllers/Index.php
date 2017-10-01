<?php

namespace App\Http\Sms\Controllers;

use App\Http\Base\Controllers\Frontend;
use Think\Verify;

class Index extends Frontend
{
    protected $mobile;
    //短信验证码
    protected $mobile_code;
    //安全码
    protected $sms_code;
    protected $flag;

    public function __construct()
    {
        parent::__construct();
        L(require(LANG_PATH . C('shop.lang') . '/other.php'));
        $this->mobile = I('mobile');
        $this->sms_code = I('verify_code');
        $this->mobile_code = I('mobile_code');
        $this->flag = I('flag');
    }

    //发送
    public function actionSend()
    {
        // 校验图片验证码
        if (!$this->check_verify($this->sms_code)) {
            exit(json_encode(array('msg' => '图片验证码不正确')));
        }

        if (empty($this->mobile)) {
            exit(json_encode(array('msg' => L('mobile_notnull'))));
        }

        // 验证手机号格式
        if (is_mobile($this->mobile) == false) {
            exit(json_encode(array('msg' => L('mobile_format_error'))));
        }
        if ($_SESSION['sms_mobile']) {
            if (strtotime($this->read_file($this->mobile)) > (time() - 60)) {
                exit(json_encode(array('msg' => L('msg_wait_auth_code'))));
            }
        }

        $where['mobile_phone'] = $this->mobile;
        $user_id = $this->db->getOne("SELECT user_id FROM {pre}users WHERE mobile_phone='" . $where['mobile_phone'] . "'");
        if ($this->flag == 'register') {
            //手机注册
            if (!empty($user_id)) {
                exit(json_encode(array('msg' => L('change_mobile'))));
            }
        } elseif ($this->flag == 'forget') {
            //找回密码
            if (empty($user_id)) {
                exit(json_encode(array('msg' => L('mobile_number_Unknown'))));
            }
        }
        $this->mobile_code = $this->random(6, 1);
        $message = array(
            'code' => $this->mobile_code
        );

        $send_time = 'sms_code';
        if ($this->flag == 'register') {
            $send_time = 'sms_signin';
            $message['product'] = $this->mobile;
        }

        $send_result = send_sms($this->mobile, $send_time, $message);
        $this->write_file($this->mobile, $send_result);
        if ($send_result === true) {
            $_SESSION['sms_mobile'] = $this->mobile;
            $_SESSION['sms_mobile_code'] = $this->mobile_code;
            exit(json_encode(array('code' => 2, 'msg' => L('send_auth_code'))));
        } else {
            // exit(json_encode(array('msg' => $send_result)));
            exit(json_encode(array('msg' => '短信发送失败')));
        }
    }

    //验证
    public function actionCheck()
    {
        if ($this->mobile != $_SESSION['sms_mobile'] or $this->mobile_code != $_SESSION['sms_mobile_code']) {
            exit(json_encode(array('msg' => L('mobile_auth_code_error'), 'code' => 1)));
        } else {
            exit(json_encode(array('code' => '2')));
        }
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    private function check_verify($code, $id = '')
    {
        $verify = new Verify();
        return $verify->check($code, $id);
    }

    private function random($length = 6, $numeric = 0)
    {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if ($numeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    private function write_file($file_name, $content)
    {
        $this->mkdirs(ROOT_PATH . 'storage/logs/sms/' . date('Ymd'));
        $filename = ROOT_PATH . 'storage/logs/sms/' . date('Ymd') . '/' . $file_name . '.log';
        $Ts = fopen($filename, "a+");
        fputs($Ts, "\r\n" . $content);
        fclose($Ts);
    }

    private function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return true;
        }
        if (!$this->mkdirs(dirname($dir), $mode)) {
            return false;
        }
        return @mkdir($dir, $mode);
    }

    private function read_file($file_name)
    {
        $content = '';
        $filename = ROOT_PATH . 'storage/logs/sms/' . date('Ymd') . '/' . $file_name . '.log';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        $content = explode("\r\n", $content);
        return end($content);
    }
}
