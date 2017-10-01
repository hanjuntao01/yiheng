
ALTER TABLE  `{pre}drp_shop` ADD  `type` tinyint(1) NOT NULL DEFAULT '2 ' COMMENT '0全部，1分类，2商品'  AFTER  `shop_points`;

CREATE TABLE IF NOT EXISTS `{pre}drp_type` (
    `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '',
    `user_id` mediumint(8) NOT NULL DEFAULT '0'  COMMENT '',
    `cat_id` int(10) NOT NULL DEFAULT '0'  COMMENT '',
    `goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '',
    `type` int(1) NOT NULL COMMENT '',
    `add_time` int(10) NOT NULL COMMENT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;