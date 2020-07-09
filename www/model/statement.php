<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//statementsテーブルから購入履歴情報+合計金額を取得
//オーダーIDごと
function get_statements($db,$order_id){
  $sql = "
    SELECT
      item_name,
      price,
      amount
    FROM
      statements
    WHERE
      order_id = ?
    ";
    return fetch_all_query($db, $sql, [$order_id]);
}
?>