<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
header('X-Frame-Options: DENY');
session_start();


function order_details($db,$order_id, $item_id, $item_price, $item_amount){
    $sql = "
    INSERT INTO
      details(
        order_id
        item_id,
        item_price,
        item_amount
      )
    VALUES(?,?,?,?);
  ";

  return execute_query($db, $sql,array($order_id, $item_id, $item_price, $item_amount));
}

