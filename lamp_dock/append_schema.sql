-- 
-- テーブルの構造 'logs'
--
CREATE TABLE `logs` (
    `order_id` INT NOT NULL AUTO_INCREMENT,
    `order_date` DATETIME,
    `user_id` INT NOT NULL,
    PRIMARY KEY(`order_id`)
);

--
-- テーブルの構造 'logs_info'
--
CREATE TABLE `logs_info` (
    `order_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    `price` INT NOT NULL DEFAULT 0,
    `amount` INT NOT NULL DEFAULT 0,
    PRIMARY KEY('order_id')
);