CREATE TABLE orders (
  order_id INT AUTO_INCREMENT,
  user_id INT,
  order_date DATETIME,
  primary key(order_id)
);

CREATE TABLE order_details (
  detail_id INT,
  order_id INT,
  item_name INT,
  price INT,
  quantity INT
);