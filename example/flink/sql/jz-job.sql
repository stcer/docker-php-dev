drop table if exists source_expo_access;
-- 创建源表
CREATE TABLE source_expo_access
(
    id         INT,
    uid       INT,
    clerk_uid       INT,
    path       VARCHAR(255),
    info_type       VARCHAR(255),
    info_id        INT,
    info_uid        INT,
    shop_id        INT,
    from_type        INT,
    share_uid        INT,
    share_sid        INT,
    share_clerk_id        INT,
    weblabel        INT,
    eid        INT,
    is_share        INT,
    cdate TIMESTAMP(3),
    PRIMARY KEY (id) NOT ENFORCED
) WITH (
    'connector' = 'mysql-cdc',
    'hostname' = '192.168.0.178',
    'port' = '3306',
    'username' = 'jc001',
    'password' = 'jc001',
    'database-name' = 'j_market',
    'table-name' = 'expo_access',
    'scan.startup.mode' = 'initial',
    'jdbc.properties.useSSL' = 'false',
    'jdbc.properties.characterEncoding' = 'UTF-8'
    'server-time-zone' = 'Asia/Shanghai'
);

-- 创建目标表
CREATE TABLE dest_expo_access
(
    id         INT,
    uid       INT,
    clerk_uid       INT,
    path       VARCHAR(255),
    info_type       VARCHAR(255),
    info_id        INT,
    info_uid        INT,
    shop_id        INT,
    from_type        INT,
    share_uid        INT,
    share_sid        INT,
    share_clerk_id        INT,
    weblabel        INT,
    eid        INT,
    is_share        INT,
    cdate TIMESTAMP(3),
    PRIMARY KEY (id) NOT ENFORCED
) WITH (
    'connector' = 'jdbc',
    'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
    'table-name' = 'expo_access',
    'username' = 'flink',
    'password' = 'flink'
);

-- 创建作业
INSERT INTO dest_expo_access SELECT * FROM source_expo_access;

-- --------------------------------------------------------------
-- --------------------------------------------------------------
-- --------------------------------------------------------------
drop table if exists source_expo_access_up;
-- 2.1 创建源表
CREATE TABLE source_expo_access_up
(
    id         INT,
    uid       INT,
    clerk_uid       INT,
    path       VARCHAR(255),
    info_type       VARCHAR(255),
    info_id        INT,
    info_uid        INT,
    shop_id        INT,
    from_type        INT,
    share_uid        INT,
    share_sid        INT,
    share_clerk_id        INT,
    weblabel        INT,
    eid        INT,
    is_share        INT,
    cdate TIMESTAMP(3),
    WATERMARK FOR cdate AS cdate - INTERVAL '5' SECOND,
    PRIMARY KEY (id) NOT ENFORCED
) WITH (
    'connector' = 'mysql-cdc',
    'hostname' = '192.168.0.178',
    'port' = '3306',
    'username' = 'jc001',
    'password' = 'jc001',
    'database-name' = 'j_market',
    'table-name' = 'expo_access',
    'scan.startup.mode' = 'initial',
    'jdbc.properties.useSSL' = 'false',
    'jdbc.properties.characterEncoding' = 'UTF-8',
    'debezium.snapshot.locking.mode' = 'none',
    'server-time-zone' = 'Asia/Shanghai'
);

-- 2.2 创建目标表
CREATE TABLE dest_expo_access_uv_pv (
   window_start TIMESTAMP(3),
   window_end TIMESTAMP(3),
   pv BIGINT,
   uv BIGINT
) WITH (
    'connector' = 'jdbc',
    'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
    'table-name' = 'expo_access_uv_pv',
    'username' = 'flink',
    'password' = 'flink'
);

CREATE TABLE dest_expo_access_up_hour (
    window_start TIMESTAMP(3),
    window_end TIMESTAMP(3),
    pv BIGINT,
    uv BIGINT
) WITH (
    'connector' = 'jdbc',
    'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
    'table-name' = 'expo_access_up_hour',
    'username' = 'flink',
    'password' = 'flink'
);

CREATE TABLE dest_expo_access_up_day (
    window_start TIMESTAMP(3),
    window_end TIMESTAMP(3),
    pv BIGINT,
    uv BIGINT
) WITH (
    'connector' = 'jdbc',
    'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
    'table-name' = 'expo_access_up_day',
    'username' = 'flink',
    'password' = 'flink'
);

-- 2.3 创建作业
INSERT INTO dest_expo_access_uv_pv
SELECT
    TUMBLE_START(cdate, INTERVAL '1' MINUTE) AS window_start,
    TUMBLE_END(cdate, INTERVAL '1' MINUTE) AS window_end,
    COUNT(*) AS pv,
    COUNT(DISTINCT uid) AS uv
FROM
    source_expo_access_up
GROUP BY
    TUMBLE(cdate, INTERVAL '1' MINUTE);


INSERT INTO dest_expo_access_up_hour
SELECT
    TUMBLE_START(cdate, INTERVAL '1' HOUR) AS window_start,
    TUMBLE_END(cdate, INTERVAL '1' HOUR) AS window_end,
    COUNT(*) AS pv,
    COUNT(DISTINCT uid) AS uv
FROM
    source_expo_access_up
GROUP BY
    TUMBLE(cdate, INTERVAL '1' HOUR);


INSERT INTO dest_expo_access_up_day
SELECT
    TUMBLE_START(cdate, INTERVAL '24' HOUR) AS window_start,
    TUMBLE_END(cdate, INTERVAL '24' HOUR) AS window_end,
    COUNT(*) AS pv,
    COUNT(DISTINCT uid) AS uv
FROM
    source_expo_access_up
GROUP BY
    TUMBLE(cdate, INTERVAL '24' HOUR);
