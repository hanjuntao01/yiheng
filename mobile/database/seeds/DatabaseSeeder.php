<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 微信通初始数据
         */
        $this->call(WechatModuleSeeder::class);

        /**
         * 微分销初始数据
         */
        $this->call(DRPModuleSeeder::class);

        /**
         * 拼团初始数据
         */
        $this->call(TeamModuleSeeder::class);
    }
}
