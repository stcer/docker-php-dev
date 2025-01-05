-- bin/sql-client.sh embedded
-- 创建源表
CREATE TABLE access (
    id INT,
    user_id STRING,
    page_url STRING,
    access_time TIMESTAMP(3),
    WATERMARK FOR access_time AS access_time - INTERVAL '5' SECOND
) WITH (
    'connector' = 'mysql-cdc',
    'hostname' = 'mysql-source',
    'port' = '3306',
    'username' = 'flink',
    'password' = 'flink',
    'database-name' = 'source_db',
    'table-name' = 'access',
    'scan.incremental.snapshot.enabled' = 'true',
    'scan.incremental.snapshot.chunk.key-column' = 'id',
    'scan.startup.mode' = 'latest-offset'
);

-- 创建目标表
CREATE TABLE access_uv_pv (
    window_start TIMESTAMP(3),
    window_end TIMESTAMP(3),
    pv BIGINT,
    uv BIGINT
) WITH (
    'connector' = 'jdbc',
    'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
    'table-name' = 'access_uv_pv',
    'username' = 'flink',
    'password' = 'flink'
  );

-- 创建作业
INSERT INTO access_uv_pv
SELECT
    TUMBLE_START(access_time, INTERVAL '1' MINUTE) AS window_start,
    TUMBLE_END(access_time, INTERVAL '1' MINUTE) AS window_end,
    COUNT(*) AS pv,
    COUNT(DISTINCT user_id) AS uv
FROM
    access
GROUP BY
    TUMBLE(access_time, INTERVAL '1' MINUTE);