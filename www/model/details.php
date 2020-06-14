<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';


function order_details($db,$order_id, $item_id, $item_price, $item_amount){
    $sql = "
    INSERT INTO
      details(
        order_id,
        item_id,
        item_price,
        item_amount
      )
    VALUES(?,?,?,?);
  ";

 return execute_query($db, $sql,array($order_id, $item_id, $item_price, $item_amount));
}


function select_details_price($db,$value){
  $sql ="
  SELECT
    item_id,
    item_price,
    item_amount
  FROM
    details
  WHERE
    order_id = ?
    ";
  return fetch_all_query($db, $sql, array($value) );
}