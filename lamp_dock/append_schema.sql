-- 購入履歴画面テーブル
CREATE TABLE history(
  purchased_history_id INT AUTO_INCREMENT,
  user_id INT,
  created DATETIME,
  primary key(purchased_history_id)
);

-- 購入明細画面テーブル
CREATE TABLE details(
  purchased_details_id INT AUTO_INCREMENT,
  item_id INT,
  amount INT DEFAULT 0,
  price_sum INT DEFAULT 0,
  created DATETIME,
  primary key(purchased_details_id)
);

