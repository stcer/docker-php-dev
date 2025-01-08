
CREATE TABLE `expo_access` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `uid` int(11) DEFAULT NULL,
   `clerk_uid` int(11) DEFAULT NULL COMMENT '用户专属导购ID',
   `path` varchar(145) DEFAULT NULL,
   `info_type` varchar(45) DEFAULT NULL,
   `info_id` int(11) DEFAULT NULL,
   `info_uid` int(11) DEFAULT '0',
   `shop_id` int(11) DEFAULT NULL COMMENT '信息所属店铺',
   `cdate` datetime DEFAULT NULL,
   `c_month` int(11) DEFAULT NULL,
   `c_year` int(11) DEFAULT NULL,
   `c_week` int(11) DEFAULT NULL,
   `c_day` date DEFAULT NULL,
   `from_type` int(11) DEFAULT '1',
   `share_id` varchar(45) DEFAULT NULL,
   `share_uid` int(11) DEFAULT NULL,
   `share_sid` int(11) DEFAULT NULL,
   `share_clerk_id` int(11) DEFAULT NULL,
   `weblabel` int(11) DEFAULT NULL COMMENT '扩展所用',
   `from_scene` varchar(45) DEFAULT NULL COMMENT '进入小程序来源',
   `chnl_id` varchar(45) DEFAULT NULL COMMENT '推广渠道',
   `eid` int(11) NOT NULL DEFAULT '0',
   `channel_id` varchar(60) DEFAULT NULL,
   `is_share` tinyint(1) DEFAULT '2' COMMENT '是否为分享1是2否',
   PRIMARY KEY (`id`),
   KEY `uid` (`uid`),
   KEY `info` (`info_type`,`info_id`),
   KEY `shop_id` (`shop_id`),
   KEY `cdate` (`cdate`),
   KEY `date_unit` (`c_year`,`c_month`,`c_day`),
   KEY `c_week` (`c_week`),
   KEY `share_id` (`share_id`),
   KEY `info_uid` (`info_uid`),
   KEY `from_scene` (`from_scene`),
   KEY `eid` (`eid`),
   KEY `sid` (`share_sid`),
   KEY `share_uid` (`share_uid`),
   KEY `chnl_id` (`chnl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- 目标表
CREATE TABLE dest_db.expo_access_uv_pv
(
    id           INT AUTO_INCREMENT PRIMARY KEY, -- 自增主键
    window_start DATETIME NOT NULL,              -- 窗口开始时间
    window_end   DATETIME NOT NULL,              -- 窗口结束时间
    pv           BIGINT   NOT NULL,              -- 页面浏览量
    uv           BIGINT   NOT NULL,              -- 独立访客数
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (window_start, window_end)             -- 为窗口开始和结束时间创建复合索引，以加速基于时间范围的查询
);

CREATE TABLE dest_db.expo_access_up_hour
(
    id           INT AUTO_INCREMENT PRIMARY KEY, -- 自增主键
    window_start DATETIME NOT NULL,              -- 窗口开始时间
    window_end   DATETIME NOT NULL,              -- 窗口结束时间
    pv           BIGINT   NOT NULL,              -- 页面浏览量
    uv           BIGINT   NOT NULL,              -- 独立访客数
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (window_start, window_end)             -- 为窗口开始和结束时间创建复合索引，以加速基于时间范围的查询
);

CREATE TABLE dest_db.expo_access_up_day
(
    id           INT AUTO_INCREMENT PRIMARY KEY, -- 自增主键
    window_start DATETIME NOT NULL,              -- 窗口开始时间
    window_end   DATETIME NOT NULL,              -- 窗口结束时间
    pv           BIGINT   NOT NULL,              -- 页面浏览量
    uv           BIGINT   NOT NULL,              -- 独立访客数
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (window_start, window_end)             -- 为窗口开始和结束时间创建复合索引，以加速基于时间范围的查询
);
