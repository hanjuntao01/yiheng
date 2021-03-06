<?php

namespace App\Http\Wechat\Plugins\Zjd;

use App\Http\Wechat\Controllers\Plugin;

/**
 * 砸金蛋
 *
 * @author wanglu
 *
 */
class Zjd extends Plugin
{
    // 插件名称
    protected $plugin_name = '';
    // 微信通ID
    protected $wechat_id = 0;
    // 商家ID
    protected $ru_id = 0;
    // 配置
    protected $cfg = array();

    /**
     * 构造方法
     *
     * @param unknown $cfg
     */
    public function __construct($cfg = array())
    {
        parent::__construct();
        $this->plugin_name = strtolower(basename(__FILE__, '.php'));
        $this->cfg = $cfg;
        $this->ru_id = $this->cfg['ru_id'];
    }

    /**
     * 安装
     */
    public function install()
    {
        // 编辑
        if (!empty($this->cfg['handler']) && is_array($this->cfg['config'])) {
            // url处理
            if (!empty($this->cfg['config']['plugin_url'])) {
                $this->cfg['config']['plugin_url'] = html_out($this->cfg['config']['plugin_url']);
            }
            // 奖品处理
            if (is_array($this->cfg['config']['prize_level']) && is_array($this->cfg['config']['prize_count']) && is_array($this->cfg['config']['prize_prob']) && is_array($this->cfg['config']['prize_name'])) {
                foreach ($this->cfg['config']['prize_level'] as $key => $val) {
                    $this->cfg['config']['prize'][] = array(
                        'prize_level' => $val,
                        'prize_name' => $this->cfg['config']['prize_name'][$key],
                        'prize_count' => $this->cfg['config']['prize_count'][$key],
                        'prize_prob' => $this->cfg['config']['prize_prob'][$key]
                    );
                }
            }
        }
        $this->plugin_display('install', $this->cfg);
    }

    /**
     * 获取数据
     */
    public function returnData($fromusername, $info)
    {
        $articles = array('type' => 'text', 'content' => '未启用砸金蛋');
        // 插件配置
        $config = $this->get_config($this->plugin_name);
        // 页面信息
        if (isset($config['media']) && !empty($config['media'])) {
            // 数据
            $articles = array();
            $articles['type'] = 'news';
            $articles['content'][0]['Title'] = $config['media']['title'];
            $articles['content'][0]['Description'] = empty($config['media']['digest']) ? sub_str($config['media']['content'], 100) : $config['media']['digest'];
            $articles['content'][0]['PicUrl'] = get_wechat_image_path($config['media']['file']);
            $articles['content'][0]['Url'] = html_out($config['media']['link']);
        }

        return $articles;
    }


    /**
     * 页面显示
     */
    public function html_show()
    {
        // 插件配置
        $config = $this->get_config($this->plugin_name);

        if (empty($config)) {
            show_message('活动未配置', '', '', 'error');
        }

        if (!empty($config)) {
            $num = count($config['prize']);
            if ($num > 0) {
                foreach ($config['prize'] as $key => $val) {
                    // 删除最后一项未中奖
                    if ($key == ($num - 1)) {
                        unset($config['prize'][$key]);
                    }
                }
            }
        }

        $starttime = local_strtotime($config['starttime']);
        $endtime = local_strtotime($config['endtime']);
        $nowtime = gmtime();

        // 用户抽奖剩余的次数
        if ($this->ru_id > 0) {
            $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
        } else {
            $openid = $_SESSION['openid'];
        }

        if ($starttime > $nowtime || $endtime < $nowtime) {
            $config['prize_num'] = 0;
            if ($nowtime < $starttime) {
                $config['activity_status'] = 1; // 活动状态 未开始
            }
            if ($nowtime > $endtime) {
                $config['activity_status'] = 2; // 活动状态 活动已结束
            }
        } else {
            if ($config['point_status'] == 1) {
                $users = get_wechat_user_id($openid);
                $user_pay_points = dao('users')->where(array('user_id' => $users['user_id']))->getField('pay_points');
                $config['user_pay_points'] = $user_pay_points; //显示会员消费积分
                //会员消费积分除以后台填写积分得出次数
                $num = floor($user_pay_points / $config['point_value']);
                $config['prize_num'] = ($config['point_value'] > 0) ? ($num < 0 ? 0 : $num) : 0;
            } else {
                // 商家抽奖次数 判断 起止时间
                if ($this->ru_id > 0) {
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline between '" . $starttime . "' AND '" . $endtime . "'";
                    $num = $GLOBALS['db']->query($sql);
                } else {
                    // 平台判断 起止时间
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline > '" . ($nowtime - $config['point_interval']) . "' ";
                    $num = $GLOBALS['db']->query($sql);
                }
                $count = isset($num[0]) ? $num[0]['num'] : 0;
                $config['prize_num'] = ($config['prize_num'] - $count) < 0 ? 0 : $config['prize_num'] - $count;
            }
            $config['activity_status'] = 0; // 活动状态 正在进行
        }

        // 中奖记录 但不含用户本人
        $sql = 'SELECT u.nickname, p.prize_name, p.id FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid where p.wechat_id = "' . $this->wechat_id . '" and p.openid != "' . $openid . '" and dateline between "' . $starttime . '" and "' . $endtime . '" and p.prize_type = 1 and p.activity_type = "' . $this->plugin_name . '" ORDER BY dateline desc limit 10';
        $list = $GLOBALS['db']->query($sql);
        $this->assign('list', $list);

        // 用户个人中奖记录 显示1条在前面, 并显示链接跳转到填写中奖地址页面
        $sql_one = 'SELECT u.nickname, p.prize_name, p.id, p.winner  FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.wechat_id = "' . $this->wechat_id . '" and p.openid = "' . $openid . '"  and dateline between "' . $starttime . '" and "' . $endtime . '" and p.prize_type = 1 and p.activity_type = "' . $this->plugin_name . '" ORDER BY dateline desc limit 1';
        $list_oneself = $GLOBALS['db']->query($sql_one);
        if (!empty($list_oneself)) {
            $list_oneself[0]['winner_url'] = url('wechat/index/plugin_action', array('name' => $this->plugin_name, 'id' => $list_oneself[0]['id'], 'ru_id' => $this->ru_id));
        }
        $this->assign('list_oneself', $list_oneself);

        $config['description'] = nl2br($config['description']);
        $this->assign('prize_num', count($config['prize'])); // 奖项数量
        $this->assign('data', $config);

        $this->assign('ru_id', $this->ru_id);

        $is_wechat = (is_wechat_browser() && is_dir(APP_WECHAT_PATH)) ? 1 : 0;
        $this->assign('is_wechat', $is_wechat);
        // 微信JSSDK分享
        $share_data = array(
            'title' => $config['media']['title'], //分享标题
            'desc' => $config['media']['digest'], //分享描述
            'link' => html_out($config['media']['link']), //分享链接
            'img' => get_wechat_image_path($config['media']['file']), //分享图片
        );
        $this->assign('share_data', $share_data);
        $this->show_display('index', $this->cfg);
    }

    /**
     * 行为操作
     */
    public function executeAction()
    {
        // 插件配置
        $config = $this->get_config($this->plugin_name);
        // 信息提交
        if (IS_POST) {
            $id = I('post.id');
            $data = I('post.data');
            if (empty($id)) {
                show_message('请选择中奖的奖品', '', '', 'error');
            }
            if (empty($data['phone'])) {
                show_message('请填写手机号', '', '', 'error');
            }
            if (empty($data['address'])) {
                show_message('请填写详细地址', '', '', 'error');
            }
            $winner['winner'] = serialize($data);

            dao('wechat_prize')->data($winner)->where(array('id' => $id, 'wechat_id' => $this->wechat_id))->save();
            show_message('资料提交成功，请等待发放奖品', '继续抽奖', url('wechat/index/plugin_show', array('name' => $this->plugin_name, 'ru_id' => $this->ru_id)));
            exit();
        }
        // 获奖用户资料填写页面
        if (!empty($_GET['id']) && !IS_AJAX) {
            $id = I('get.id');
            if ($this->ru_id > 0) {
                $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
            } else {
                $openid = $_SESSION['openid'];
            }
            if (empty($openid)) {
                show_message('请先登录', '', '', 'error');
            }
            $rs = dao('wechat_prize')
                ->field('winner,issue_status')
                ->where(array('openid' => $openid, 'id' => $id, 'wechat_id' => $this->wechat_id, 'prize_type' => 1, 'activity_type' => $this->plugin_name))
                ->find();
            $winner_result = array();

            if (!empty($rs) && $rs['issue_status'] != 1) {
                if (!empty($rs['winner'])) {
                    $winner_result = unserialize($rs['winner']);
                } else {
                    // 查询上一次中奖记录 联系地址
                    $sql = "SELECT winner FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND id < '" . $id . "'  ORDER by dateline DESC LIMIT 1";
                    $rs1 = $GLOBALS['db']->query($sql);
                    if (!empty($rs1)) {
                        $previous_winner_result = unserialize($rs1[0]['winner']);
                    }
                }
            } else {
                show_message('本次奖品已经领取过了哦', '', '', 'error');
            }
            // 如果有上一次的中奖地址，获取上一次的，默认取本次填写的
            $winner_result = !empty($previous_winner_result) ? $previous_winner_result : $winner_result;

            if ($this->ru_id > 0) {
                $file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/Views_seller/user_info.html';
            } else {
                $file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/Views/user_info.html';
            }
            if (file_exists($file)) {
                require_once($file);
            }
            exit();
        }
        // 抽奖操作
        if (IS_GET && IS_AJAX) {
            $rs = array();
            // 未登录
            if ($this->ru_id > 0) {
                $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
            } else {
                $openid = $_SESSION['openid'];
            }
            if (empty($openid)) {
                $rs['status'] = 2;
                $rs['msg'] = '请先登录';
                echo json_encode($rs);
                exit();
            }

            // 活动过期
            $starttime = local_strtotime($config['starttime']);
            $endtime = local_strtotime($config['endtime']);

            $nowtime = gmtime();
            if ($nowtime < $starttime) {
                $rs['status'] = 2;
                $rs['msg'] = '活动未开始';
                echo json_encode($rs);
                exit();
            }
            if ($nowtime > $endtime) {
                $rs['status'] = 2;
                $rs['msg'] = '活动已结束';
                echo json_encode($rs);
                exit();
            }
            // 超过次数
            // 商家 抽奖次数判断 起止时间
            if ($this->ru_id > 0) {
                $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline between '" . $starttime . "' AND '" . $endtime . "'";
                $count_num = $GLOBALS['db']->query($sql);
                $num = isset($count_num[0]) ? $count_num[0]['num'] : 0;
                if ($num <= 0) {
                    $num = 1;
                } else {
                    $num = $num + 1;
                }
            } else {
                if ($config['point_status'] == 1) {
                    $users = get_wechat_user_id($openid);
                    $user_pay_points = dao('users')->where(array('user_id' => $users['user_id']))->getField('pay_points');
                    $num = floor($user_pay_points / $config['point_value']) < 0 ? 0 : floor($user_pay_points / $config['point_value']);
                } else {
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE  wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline > '" . ($nowtime - $config['point_interval']) . "'";
                    $count_num = $GLOBALS['db']->query($sql);
                    $num = isset($count_num[0]) ? $count_num[0]['num'] : 0;

                    if ($num <= 0) {
                        $num = 1;
                    } else {
                        $num = $num + 1;
                    }
                }
            }

            if ($config['point_status'] == 1) {
                // 积分扣除
                $res = $this->do_takeout_point($openid, $config, $config['point_value']);
                if ($res === false) {
                    $rs['status'] = 2;
                    $rs['msg'] = "积分不够了";
                    echo json_encode($rs);
                    exit();
                }
            } else {
                if ($num > $config['prize_num']) {
                    $rs['status'] = 2;
                    $rs['num'] = 0;
                    $rs['msg'] = '你已经用光了抽奖次数';
                    echo json_encode($rs);
                    exit();
                }
            }

            $prize = $config['prize'];
            if (!empty($prize)) {
                $arr = array();
                $prize_name = array();

                foreach ($prize as $key => $val) {
                    // 删除数量不足的奖品
                    $count = dao('wechat_prize')
                        ->where(array('wechat_id' => $this->wechat_id, 'prize_name' => $val['prize_name'], 'activity_type' => $this->plugin_name))
                        ->count();
                    if ($count >= $val['prize_count']) {
                        unset($prize[$key]);
                    } else {
                        $arr[$val['prize_level']] = $val['prize_prob'];
                        $prize_name[$val['prize_level']] = $val['prize_name'];
                    }
                }
                // 最后一个奖项
                $lastarr = end($prize);
                // 获取中奖项
                $level = $this->get_rand($arr);
                // 0为未中奖,1为中奖
                if ($level == $lastarr['prize_level']) {
                    $rs['status'] = 0;
                    $data['prize_type'] = 0;
                } else {
                    $rs['status'] = 1;
                    $data['prize_type'] = 1;
                }
                $rs['msg'] = $prize_name[$level];

                if ($config['point_status'] == 1) {
                    $rs['num'] = $num - 1;//
                } else {
                    $rs['num'] = $config['prize_num'] - $num > 0 ? $config['prize_num'] - $num : 0;
                }

                // 抽奖记录
                $data['wechat_id'] = $this->wechat_id;
                $data['openid'] = $openid;
                $data['prize_name'] = $prize_name[$level];
                $data['dateline'] = gmtime();
                $data['activity_type'] = $this->plugin_name;
                $id = dao('wechat_prize')->data($data)->add();

                //参与人数增加
                $extend_cfg = dao('wechat_extend')->where(array('wechat_id' => $this->wechat_id, 'command' => $this->plugin_name, 'enable' => 1))->getField('config');
                if ($extend_cfg) {
                    $cfg_new = unserialize($extend_cfg);
                }
                $cfg_new['people_num'] = $cfg_new['people_num'] + 1;
                $cfg['config'] = serialize($cfg_new);
                dao('wechat_extend')->where(array('wechat_id' => $this->wechat_id, 'command' => $this->plugin_name, 'enable' => 1))->data($cfg)->save();

                if ($level != $lastarr['prize_level'] && !empty($id)) {
                    // 获奖链接
                    $rs['link'] = url('wechat/index/plugin_action', array('name' => $this->plugin_name, 'id' => $id, 'ru_id' => $this->ru_id));
                    $rs['link'] = str_replace('&amp;', '&', $rs['link']);
                }
            }
            echo json_encode($rs);
            exit();
        }
    }

    public function updatePoint($fromusername, $info)
    {
    }


    /**
     * 获取插件配置信息
     *
     * @param string $code
     * @return multitype:unknown
     */
    private function get_config($code = '')
    {
        // 公众号信息
        $config = array();
        if ($this->ru_id > 0) {
            $map = array('ru_id' => $this->ru_id, 'status' => 1);
        } else {
            $map = array('default_wx' => 1, 'status' => 1);
        }
        $this->wechat_id = dao('wechat')->where($map)->getField('id');
        if (!empty($this->wechat_id)) {
            $plugin_config = dao('wechat_extend')->field('name, command, config')->where(array('wechat_id' => $this->wechat_id, 'command' => $code, 'enable' => 1))->find();
            if (!empty($plugin_config)) {
                $config = unserialize($plugin_config['config']);
                $config['name'] = $plugin_config['name'];
                $config['command'] = $plugin_config['command'];
                // 素材
                if (!empty($config['media_id'])) {
                    $media = dao('wechat_media')
                        ->field('id, title, file, file_name, type, digest, content, add_time, article_id, link')
                        ->where(array('id' => $config['media_id'], 'wechat_id' => $this->wechat_id))
                        ->find();
                    // 单图文
                    if (empty($media['article_id'])) {
                        $media['content'] = strip_tags(html_out($media['content']));
                        $config['media'] = $media;
                    }
                }
                // url处理
                if (!empty($config['plugin_url'])) {
                    $config['plugin_url'] = html_out($config['plugin_url']);
                }
                // 奖品处理
                if (is_array($config['prize_level']) && is_array($config['prize_count']) && is_array($config['prize_prob']) && is_array($config['prize_name'])) {
                    foreach ($config['prize_level'] as $key => $val) {
                        $config['prize'][] = array(
                            'prize_level' => $val,
                            'prize_name' => $config['prize_name'][$key],
                            'prize_count' => $config['prize_count'][$key],
                            'prize_prob' => $config['prize_prob'][$key]
                        );
                    }
                }
            }
        }
        return $config;
    }
}
