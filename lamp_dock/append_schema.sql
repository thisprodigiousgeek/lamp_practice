create table histories
(history_id int AUTO_INCREIMENT,
user_id int,
create_datetime datetime,
primary key(history_id));

create table history_dateils
(history_dateil_id int AUTO_INCREIMENT,
history_id int,
item_id int,
amount int,
price int,
create_datetime datetime,
primary key(history_dateil_id));