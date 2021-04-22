-- 購入履歴テーブルの作成
CREATE TABLE orders (
order_id int(11) AUTO_INCREMENT,
user_id int(11) NOT NULL,
created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
primary KEY(order_id),
FOREIGN KEY(user_id) REFERENCES users(user_id) 
)

-- 購入明細テーブルの作成
CREATE TABLE details (
details_id int(11) AUTO_INCREMENT,
order_id int(11) NOT NULL,
item_id int(11) NOT NULL,
price int(11) NOT NULL,
amount int(11) NOT NULL,
primary key(details_id),
FOREIGN KEY(order_id) REFERENCES orders(order_id),
FOREIGN KEY(item_id) REFERENCES items(item_id)
)

-- インデックスの作成
CREATE INDEX user_id ON orders(user_id);
CREATE INDEX order_id ON details(order_id);
