<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//order_productsテーブルから購入履歴情報+合計金額を取得
//ユーザーIDごと　サブクエリで合計金額を取得。
function get_orders($db,$user_id){
    $sql = "
      SELECT
        order_id,
        order_datetime,
        (SELECT SUM(price*amount) FROM statements WHERE order_id = order_products.order_id) AS total
      FROM
        order_products
      WHERE
        user_id = ?
      ORDER BY
        order_datetime DESC
      ";
    return fetch_all_query($db, $sql, [$user_id]);
}

//adminでログイン中の場合に、購入履歴を全件取得する関数
function get_admin_orders($db){
    $sql = "
      SELECT
        order_id,
        order_datetime,
        (SELECT SUM(price*amount) FROM statements WHERE order_id = order_products.order_id) AS total
      FROM
        order_products
      ORDER BY
        order_datetime DESC
      ";
    return fetch_all_query($db, $sql);
}


function sum_orders($statements){
  $order_total_price = 0;
  foreach($statements as $statement){
    $order_total_price += $statements['price'] * $statement['amount'];
  }
  return $order_total_price;
}
?>