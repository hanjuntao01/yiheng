<?php

namespace App\Extensions;

class Wxapp
{
    /**
     * 微信小程序类
     * 官方文档：https://mp.weixin.qq.com/debug/wxadoc/dev/index.html?t=20161107
     */
    const API_URL_PREFIX = 'https://api.weixin.qq.com';

    const AUTH_ORIZATION = '/sns/jscode2session?'; // 获取登录凭证（code）

    private $wx_mini_appid; // 小程序ID

    private $wx_mini_secret; // 小程序密钥

    public $debug =  false;

    public $errCode = 40001;

    public $errMsg = "no access";

    public function __construct(array $options)
    {
        $this->wx_mini_appid = isset($options['appid']) ? $options['appid'] : '';
        $this->wx_mini_secret = isset($options['secret']) ? $options['secret'] : '';
    }

    /**
     * code 换取 session_key
     * 调用接口获取登录凭证（code）
     * @param
     * @return
     */
    public function getOauthOrization($code)
    {
        $params = [
            'appid' => $this->wx_mini_appid,
            'secret' => $this->wx_mini_secret,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ];
        $result = $this->curlGet(self::API_URL_PREFIX.self::AUTH_ORIZATION.http_build_query($params, '', '&'));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }


    /**
     * GET 请求
     * @param string $url
     */
    private function curlGet($url, $timeout = 5, $header = "")
    {
        $ch = curl_init();
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));//模拟的header头
        $result = curl_exec($ch);
        $aStatus = curl_getinfo($ch);
        curl_close($ch);
        if (intval($aStatus["http_code"])==200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function curlPost($url, $post_data, $timeout = 5)
    {
        $ch = curl_init();
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        $header = empty($header) ? '' : $header;
        if (is_string($post_data)) {
            $strPOST = $post_data;
        } else {
            $aPOST = array();
            foreach ($post_data as $key=>$val) {
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));//模拟的header头
        $result = curl_exec($ch);
        $aStatus = curl_getinfo($ch);
        curl_close($ch);
        if (intval($aStatus["http_code"])==200) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 日志记录
     * @param mixed $log 输入日志
     * @return mixed
     */
    public function log($log)
    {
        $log = is_array($log) ? var_export($log, true) : $log;
        if ($this->debug && function_exists('logResult')) {
            logResult($log);
        }
    }

    /**
     * 设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired)
    {
        return S($cachename, $value, $expired);
    }

    /**
     * 获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename)
    {
        return S($cachename);
    }

    /**
     * 清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename)
    {
        return S($cachename, null);
    }
}
