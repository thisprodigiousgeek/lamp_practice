create table history
(history_id int AUTO_INCREMENT NOT NULL,
user_id int NOT NULL,
create_datetime datetime,
primary key(history_id));

create table purchase_detail
(purchase_detail_id int AUTO_INCREMENT NOT NULL,
history_id int NOT NULL,
item_id int NOT NULL,
amount int NOT NULL,
price int NOT NULL,
primary key(purchase_detail_id));
