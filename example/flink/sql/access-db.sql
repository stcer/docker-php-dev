-- 源表
CREATE TABLE source_db.access
(
    id          INT AUTO_INCREMENT PRIMARY KEY,      -- 自增主键
    user_id     VARCHAR(255) NOT NULL,               -- 用户ID
    page_url    VARCHAR(255) NOT NULL,               -- 页面URL
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 访问时间，默认为当前时间
    INDEX (access_time)                              -- 为访问时间创建索引，以加速基于时间的查询
);


INSERT INTO source_db.access (user_id, page_url, access_time)
VALUES (1, "home", "2024-12-01 12:12:00"),
       (2, "goods", "2024-12-01 12:13:00"),
       (2, "news", "2024-12-01 12:13:10")
;

INSERT INTO source_db.access (user_id, page_url, access_time)
VALUES (1, "home", "2024-12-03 12:12:00"),
       (2, "goods", "2024-12-03 12:13:00"),
       (2, "news", "2024-12-03 12:14:10")
;

-- 目标表
CREATE TABLE dest_db.access_uv_pv
(
    id           INT AUTO_INCREMENT PRIMARY KEY, -- 自增主键
    window_start DATETIME NOT NULL,              -- 窗口开始时间
    window_end   DATETIME NOT NULL,              -- 窗口结束时间
    pv           BIGINT   NOT NULL,              -- 页面浏览量
    uv           BIGINT   NOT NULL,              -- 独立访客数
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (window_start, window_end)             -- 为窗口开始和结束时间创建复合索引，以加速基于时间范围的查询
);