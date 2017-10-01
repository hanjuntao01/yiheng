<?php

use Illuminate\Database\Seeder;

/**
 * Class WechatModuleSeeder
 */
class WechatModuleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->wechatTemplate();
        $this->adminAction();
    }

    /**
     * 增加微信消息模板
     */
    private function wechatTemplate()
    {
        $result = DB::table('wechat_template')->get();
        $result = $result->toArray();
        if (empty($result)) {
            // 默认数据
            $rows = [
                [
                    'wechat_id' => 1,
                    'code' => 'TM00016',
                    'template' => '订单号：{{orderID.DATA}}\r\n待付金额：{{orderMoneySum.DATA}}\r\n{{backupFieldName.DATA}}{{backupFieldData.DATA}}\r\n{{remark.DATA}}',
                    'title' => '订单提交成功'
                ],
                [
                    'wechat_id' => 1,
                    'code' => 'OPENTM204987032',
                    'template' => '{{first.DATA}}\r\n订单：{{keyword1.DATA}}\r\n支付状态：{{keyword2.DATA}}\r\n支付日期：{{keyword3.DATA}}\r\n商户：{{keyword4.DATA}}\r\n金额：{{keyword5.DATA}}\r\n{{remark.DATA}}',
                    'title' => '订单支付成功通知'
                ],
                [
                    'wechat_id' => 1,
                    'code' => 'OPENTM202243318',
                    'template' => '{{first.DATA}}\r\n订单内容：{{keyword1.DATA}}\r\n物流服务：{{keyword2.DATA}}\r\n快递单号：{{keyword3.DATA}}\r\n收货信息：{{keyword4.DATA}}\r\n{{remark.DATA}}',
                    'title' => '订单发货通知'
                ],
                [
                    'wechat_id' => 1,
                    'code' => 'OPENTM401833445',
                    'template' => '{{first.DATA}}\r\n变动时间：{{keyword1.DATA}}\r\n变动类型：{{keyword2.DATA}}\r\n变动金额：{{keyword3.DATA}}\r\n当前余额：{{keyword4.DATA}}\r\n{{remark.DATA}}',
                    'title' => '余额变动提示'
                ]
            ];
            DB::table('wechat_template')->insert($rows);
        }
    }

    /**
     * 增加微信通模块权限
     */
    private function adminAction()
    {
        $result = DB::table('admin_action')->where('action_code', 'wechat')->get();
        $result = $result->toArray();
        if (empty($result)) {
            // 默认数据
            $row = [
                'parent_id' => 0,
                'action_code' => 'wechat'
            ];
            $action_id = DB::table('admin_action')->insertGetId($row);

            // 默认数据
            $rows = [
                [
                    'parent_id' => $action_id,
                    'action_code' => 'wechat_admin'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'mass_message'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'auto_reply'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'menu'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'fans'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'media'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'qrcode'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'market'
                ],
                [
                    'parent_id' => $action_id,
                    'action_code' => 'extend'
                ]
            ];
            DB::table('admin_action')->insert($rows);
        }
    }
}