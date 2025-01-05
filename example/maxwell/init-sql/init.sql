-- 确保 Maxwell 用户存在
CREATE USER IF NOT EXISTS 'maxwell'@'%' IDENTIFIED BY 'maxwell';

-- 允许 Maxwell 访问所有库的 binlog
GRANT SELECT, REPLICATION CLIENT, REPLICATION SLAVE ON *.* TO 'maxwell'@'%';

-- 赋予 maxwell 自己数据库的权限
GRANT ALL PRIVILEGES ON maxwell.* TO 'maxwell'@'%';

-- 刷新权限
FLUSH PRIVILEGES;
