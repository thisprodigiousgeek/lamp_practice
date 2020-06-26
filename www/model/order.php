<?php

//order_productsテーブルから購入履歴情報を取得
function order_information($db){
  $sql = "
    SELECT
      order_id,
      price,
      order_datetime
    FROM
      order_products
    ";
  return fetch_query($db, $sql);
}
?>