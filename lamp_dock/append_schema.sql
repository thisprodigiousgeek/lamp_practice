-- テーブル設計課題
-- 購入履歴：order_id(AI,注文番号), user_id, order_date(購入日時)
CREATE TABLE purchase_history (
  order_id int(11) auto_increment,
  user_id int(11) NOT NULL,
  order_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 購入明細：oreder_id(=購入履歴), item_id(=items), item_amount, purchase_price(購入時の価格) 
-- ※AIなし
CREATE TABLE purchase_details (
    order_id int(11) NOT NULL,
    item_id int(11) NOT NULL,
    item_amount int(11) NOT NULL,
    purchase_price int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;