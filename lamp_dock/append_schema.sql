CREATE TABLE history (
    order_id int(10) AUTO_INCREMENT NOT NULL,
    user_id int(10) NOT NULL,
    purchase_date varchar(20) NOT NULL,
    PRIMARY KEY(order_id)
    );


CREATE TABLE  details (
    order_id int(10) NOT NULL,
    item_id int(10) NOT NULL,
    price int(10) NOT NULL,
    amount int(10) NOT NULL
    );