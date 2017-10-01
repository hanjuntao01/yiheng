CREATE TABLE IF NOT EXISTS `{pre}drp_config` (
    `id` smallint(5) NOT NULL AUTO_INCREMENT COMMENT '编号',
    `code` varchar(30) NOT NULL COMMENT '关键词',
    `type` varchar(10) NOT NULL COMMENT '字段类型',
    `store_range` varchar(255) NOT NULL COMMENT '值范围',
    `value` text NOT NULL COMMENT '值',
    `name` varchar(60) NOT NULL COMMENT '字段中文名称',
    `warning` varchar(60) NOT NULL COMMENT '提示',
    `sort_order` mediumint(8) NOT NULL COMMENT '排序',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{pre}drp_shop` (
    `id` mediumint(8) NOT NULL AUTO_INCREMENT,
    `user_id` mediumint(8) NOT NULL COMMENT '会员id',
    `shop_name` varchar(60) NOT NULL COMMENT '店铺名称',
    `real_name` varchar(60) NOT NULL COMMENT '真实姓名',
    `mobile` varchar(12) NOT NULL COMMENT '手机号',
    `qq` varchar(16) NOT NULL COMMENT 'qq',
    `shop_img` varchar(120) NOT NULL COMMENT '店铺背景图',
    `cat_id` varchar(120) NOT NULL COMMENT '分类id',
    `create_time` int(11) NOT NULL COMMENT '创建时间',
    `isbuy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否购买成为分销商',
    `audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺审核,0未审核,1已审核',
    `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺状态',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{pre}drp_log` (
    `log_id` mediumint(8) NOT NULL AUTO_INCREMENT,
    `order_id` mediumint(8) NOT NULL,
    `time` int(10) NOT NULL,
    `user_id` mediumint(8) NOT NULL,
    `user_name` varchar(60) DEFAULT NULL,
    `money` decimal(10,2) NOT NULL DEFAULT '0.00',
    `point` int(10) NOT NULL DEFAULT '0',
    `drp_level` tinyint(3) NOT NULL,
    `is_separate` tinyint(1) NOT NULL DEFAULT '0',
    `separate_type` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{pre}drp_affiliate_log` (
  `log_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) NOT NULL,
  `time` int(10) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `user_name` varchar(60) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(10) NOT NULL DEFAULT '0',
  `separate_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `{pre}goods` ADD `dis_commission` decimal( 10 ) NOT NULL COMMENT '分销佣金百分比' ;
ALTER TABLE `{pre}goods` ADD `is_distribution` INT( 1 ) NOT NULL COMMENT '商品是否参与分销';
ALTER TABLE `{pre}order_info` ADD  `drp_is_separate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单分销状态';
ALTER TABLE `{pre}order_goods` ADD `drp_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分销佣金百分比';
ALTER TABLE `{pre}order_goods` ADD `is_distribution` INT( 1 ) NOT NULL COMMENT '商品是否参与分销';
ALTER TABLE `{pre}drp_shop` ADD `shop_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得佣金';
ALTER TABLE `{pre}drp_shop` ADD `shop_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获得积分';

DELETE FROM `{pre}drp_config`;
INSERT INTO `{pre}drp_config` (`code`, `type`, `store_range`, `value`, `name`, `warning`, `sort_order`) VALUES
('notice', 'textarea', '', '亲，您的佣金由三部分组成：\r\n1.本店所销售的商品，我所获得的佣金（即本店销售佣金）\r\n2.下级分店所销售的商品，我所获得的佣金（即一级分店佣金）\r\n3.下级分店发展的分店所销售的商品，我所获得的佣金（即二级分店佣金）s', '温馨提示', '申请成为分销商时，提示用户需要注意的信息', 0),
('novice', 'textarea', '', '1、开微店收入来源之一：您已成功注册微店，已经取得整个商城的商品销售权，只要有人在您的微店购物，即可获得“本店销售佣金”。\r\n2、开微店收入来源之二：邀请您的朋友注册微店，他就会成为你的分销商，他店内销售的商品，您即可获得“一级分店佣金”。\r\n3、开微店收入来源之三：您的分销商邀请他的朋友注册微店，他店内销售的商品，您即可获得“二级分店佣金”。', '新手必读', '分销商申请成功后，用户要注意的事项', 0),
 ('withdraw', 'textarea', '', '可提现金额为交易成功后7天且为提现范围内的金额', '提现提示', '申请提现时，少于该值将无法提现', ''),
('draw_money', 'text', '', '10', '提现金额', '申请提现时，少于该值将无法提现', 0),
('issend', 'radio', '0,1', '1', '消息推送', '申请店铺成功时,推送消息到微信', 0),
('isbuy', 'radio', '0,1', '1', '购买成为分销商', '是否开启购买成为分销商,默认申请成为分销商', 0),
('buy_money', 'text', '', '100', '购买金额', '购买金额达到该数值,才能成为分销商', 0),
('isdrp', 'radio', '0,1', '1', '商品分销模式', '是否开启分销模式,默认分销模式。控制商品详情页‘我要分销’按钮', 0),
('ischeck', 'radio', '0,1', '1', '分销商审核', '成为分销商,是否需要审核', 0),
('drp_affiliate', '', '', 'a:3:{s:6:"config";a:5:{s:6:"expire";i:0;s:11:"expire_unit";s:3:"day";s:3:"day";s:1:"8";s:15:"level_point_all";s:2:"8%";s:15:"level_money_all";s:2:"1%";}s:4:"item";a:3:{i:0;a:2:{s:11:"level_point";s:3:"60%";s:11:"level_money";s:3:"60%";}i:1;a:2:{s:11:"level_point";s:3:"30%";s:11:"level_money";s:3:"30%";}i:2;a:2:{s:11:"level_point";s:3:"10%";s:11:"level_money";s:3:"10%";}}s:2:"on";i:1;}', '三级分销比例', '', 0),
('custom_distributor','text', '' ,'代言人','自定义“分销商”名称', '替换设定的分销商名称', 0),
('custom_distribution','text', '' ,'代言','自定义“分销”名称', '替换设定的分销名称', 0);

DELETE FROM `{pre}article_cat` WHERE `cat_id` = 1000;
INSERT INTO `{pre}article_cat` (`cat_id`, `cat_name`, `cat_type`, `keywords`, `cat_desc`, `sort_order`, `show_in_nav`, `parent_id`) VALUES (1000, '微分销', 1, '分销', '', 50, 1, 0);

DELETE FROM `{pre}article` WHERE `cat_id` = 1000;
INSERT INTO `{pre}article` (`cat_id`, `title`, `content`, `author`, `author_email`, `keywords`, `article_type`, `is_open`, `add_time`, `file_url`, `open_type`, `link`, `description`) VALUES ('1000', '什么是微分销？', '微分销是一体化微信分销交易平台，基于朋友圈传播，帮助企业打造“企业微商城+粉丝微店+员工微店”的多层级微信营销模式，轻松带领千万微信用户一起为您的商品进行宣传及销售。', '', '', '', '0', '1', '1467962482', '', '0', '', NULL);
INSERT INTO `{pre}article` (`cat_id`, `title`, `content`, `author`, `author_email`, `keywords`, `article_type`, `is_open`, `add_time`, `file_url`, `open_type`, `link`, `description`) VALUES ('1000', '如何申请成为分销商？', '关注微信公众号，进入会员中心点击我的微店。申请后，等待管理员审核通过，即可拥有自己的微店，坐等佣金收入分成！', '', '', '', '0', '1', '1467962482', '', '0', '', NULL);

DELETE FROM `{pre}wechat_template` WHERE `code` = 'OPENTM207126233' OR `code` = 'OPENTM201812627';
INSERT INTO `{pre}wechat_template` (`code`, `content`, `template`, `title`, `add_time`, `status`, `wechat_id`) VALUES
('OPENTM207126233', NULL, '{{first.DATA}}\r\n分销商名称：{{keyword1.DATA}}\r\n分销商电话：{{keyword2.DATA}}\r\n申请时间：{{keyword3.DATA}}\r\n{{remark.DATA}}', '分销商申请成功', 1460697723, 0, 1),
('OPENTM201812627', NULL, '{{first.DATA}}\r\n佣金金额：{{keyword1.DATA}}\r\n时间：{{keyword2.DATA}}\r\n{{remark.DATA}}', '佣金提醒', 1460698436, 0, 1);
