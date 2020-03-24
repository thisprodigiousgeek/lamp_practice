CREATE TABLE purchase_history (
    buy_id INT AUTO_INCREMENT,
    user_id INT,
    create DATETIME,
    update DATETIME,
    primary key(buy_id)
);

CREATE TABLE purchase_detail (
    buy_id INT,
    item_id INT,
    amount INT
);