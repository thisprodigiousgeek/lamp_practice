create table histories
(history_id int AUTO_INCREMENT,
user_id int NOT NULL,
create_datetime datetime NOT NULL,
primary key(history_id));

create table history_dateils
(history_dateil_id int AUTO_INCREMENT,
history_id int NOT NULL,
item_id int NOT NULL,
amount int NOT NULL,
price int NOT NULL,
create_datetime datetime NOT NULL,
primary key(history_dateil_id));