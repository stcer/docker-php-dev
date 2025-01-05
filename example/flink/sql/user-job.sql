-- bin/sql-client.sh embedded
-- 创建源表
CREATE TABLE source_users
(
    id   INT,
    name STRING,
    age  INT,
    PRIMARY KEY (id) NOT ENFORCED
) WITH (
      'connector' = 'mysql-cdc',
      'hostname' = 'mysql-source',
      'port' = '3306',
      'username' = 'flink',
      'password' = 'flink',
      'database-name' = 'source_db',
      'table-name' = 'users'
      );

-- 创建目标表
CREATE TABLE dest_users
(
    id   INT,
    name STRING,
    age  INT,
    PRIMARY KEY (id) NOT ENFORCED
) WITH (
      'connector' = 'jdbc',
      'url' = 'jdbc:mysql://mysql-dest:3306/dest_db',
      'table-name' = 'users',
      'username' = 'flink',
      'password' = 'flink'
      );

-- 创建作业
INSERT INTO dest_users
SELECT *
FROM source_users;