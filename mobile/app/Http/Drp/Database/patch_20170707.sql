--
-- 表的结构 `店铺列表 dsc_drp_shop 增加以下字段(分销商等级id)特殊等级`
--

ALTER TABLE  `{pre}drp_shop` ADD  `credit_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销商等级id'  AFTER  `type`

--
-- 表的结构 `店铺配置 dsc_drp_config 修改 drp_affiliate 分销商分成比例`
--

UPDATE  `{pre}drp_config` SET  `value` = 'a:3:{s:6:"config";a:5:{s:6:"expire";i:0;s:11:"expire_unit";s:3:"day";s:3:"day";s:1:"7";s:15:"level_point_all";s:2:"8%";s:15:"level_money_all";s:2:"1%";}s:4:"item";a:3:{i:0;a:3:{s:8:"credit_t";s:3:"30%";s:8:"credit_y";s:3:"40%";s:8:"credit_j";s:3:"50%";}i:1;a:3:{s:8:"credit_t";s:3:"10%";s:8:"credit_y";s:3:"20%";s:8:"credit_j";s:3:"30%";}i:2;a:3:{s:8:"credit_t";s:2:"5%";s:8:"credit_y";s:3:"10%";s:8:"credit_j";s:3:"20%";}}s:2:"on";i:1;}' WHERE  `code` ='drp_affiliate';

--
-- 表的结构 `店铺配置 dsc_drp_config 按时间统计分销商佣金排行`
--

INSERT INTO `{pre}drp_config` (`code`, `type`, `store_range`, `value`, `name`, `warning`, `sort_order`) VALUES
('count_commission', 'radio', '0,1,2', '2', '按时间统计分销商佣金排行', '按时间统计分销商佣金进行分销商排行，可以按 周，月，年 排行', 0),
('register', 'radio', '0,1', '1', '开启分销商店铺自动注册', '开启分销商店铺自动注册，开启之后授权登录，关注商城会自动生成一个分销商店铺，注：使用此功能必须关闭购买成为分销商，是否开启购物累计消费金额满足设置才能开店', 0);

--
-- 表的结构 `店铺列表 dsc_users 增加以下字段(分销商父级id)`
--

ALTER TABLE  `{pre}users` ADD  `drp_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销商父级id'  AFTER  `parent_id`

--
-- 表的结构 `分销商等级` dsc_drp_user_credit
--

CREATE TABLE IF NOT EXISTS `{pre}drp_user_credit` (
	`id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '',
	`credit_name` varchar(30) NOT NULL DEFAULT ''  COMMENT '等级名称',
	`min_money` int(10) NOT NULL DEFAULT '0'  COMMENT '金额下线',
	`max_money` int(10) NOT NULL DEFAULT '0' COMMENT '金额上线',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- 表的结构 `分销商等级` 增加默认数据
--

INSERT INTO `{pre}drp_user_credit` (`id`, `credit_name`, `min_money`, `max_money`) VALUES
(1, '铜牌', 0, 1000),
(2, '银牌', 1001, 2000),
(3, '金牌', 2001, 600000);

--
-- 表的结构 `佣金转出记录` dsc_drp_transfer_log
--

CREATE TABLE IF NOT EXISTS `{pre}drp_transfer_log` (
	`id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '',
	`user_id` mediumint(8) NOT NULL DEFAULT '0'  COMMENT '会员id',
	`money` decimal(10,2) NOT NULL DEFAULT '0.00'  COMMENT '转出金额',
	`add_time` int(10) NOT NULL DEFAULT '0' COMMENT '转出时间',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

