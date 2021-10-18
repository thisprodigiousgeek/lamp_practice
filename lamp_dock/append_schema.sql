-- 購入履歴テーブル
-- 注文番号、user_id、購入日時

CREATE TABLE `purchase_history`(
    `order_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    primary key(order_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 購入明細テーブル
-- 明細番号、注文番号、item_id、数量

CREATE TABLE `purchase_detail`(
    `detail_id` int(11) AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `item_id` int(11) NOT NULL,
    `price` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    primary key(detail_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

