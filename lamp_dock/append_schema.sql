    CREATE TABLE order_history(
        order_history_id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        total_price int(11) NOT NULL,
        created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        primary key(order_history_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE order_details(
        order_details_id int(11) NOT NULL AUTO_INCREMENT,
        order_history_id int(11) NOT NULL,
        item_id int(11) NOT NULL,
        price int(11) NOT NULL,
        amount int(11) NOT NULL,
        created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        primary key(order_details_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;