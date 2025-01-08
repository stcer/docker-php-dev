-- docker exec -it mysql-source mysql -u root -proot
CREATE USER 'flink'@'%' IDENTIFIED BY 'flink';
GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'flink'@'%';
FLUSH PRIVILEGES;

-- 创建源表
CREATE TABLE source_db.users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(255),
    age        INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO source_db.users (name, age)
VALUES ('Alice', 25),
       ('Bob', 30);
INSERT INTO source_db.users (name, age)
VALUES ('Stcer', 26),
       ('Simth', 35);
INSERT INTO source_db.users (name, age)
VALUES ('Stcer1', 26),
       ('Simth1', 35);

-- docker exec -it mysql-dest mysql -u root -proot
-- 创建目标表
CREATE TABLE dest_db.users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(255),
    age        INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
