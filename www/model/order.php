<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function insert_order($db, $user_id){
  // $result 初期化
  $result = false;
  $sql = "
    INSERT INTO
      orders(
        user_id
      )
    VALUES(?);
  ";
  $params = array($user_id);
  // 上記SQLの実行:成功 → $result = true / :失敗 → $result = false(結果、初期値のまま)
  $result = execute_query($db, $sql, $params);
  // 成功の場合、
  if($result === true){
    // <登録したデータのID>を取得し、$resultに代入。
    $result = $db->lastInsertId();
  }
  return $result;
}

function insert_order_detail($db, $carts, $order_id){
  $result = false;
  foreach($carts as $cart){
    $sql = "
      INSERT INTO
        order_details(
        order_id,
        item_id,
        purchase_price,
        quantity
        )
      VALUES(?, ?, ?, ?);
    ";
    $params = array($order_id, $cart['item_id'], $cart['price'], $cart['amount']);
    $result = execute_query($db, $sql, $params);
    if($result === false){
      break;
    }
  }
  return $result;
}

function regist_order_transaction($db, $user_id, $order_id, $item_id, $price, $amount){
  $db->beginTransaction();
  if(insert_order($db, $user_id) 
    && insert_order_detail($order_id, $item_id, $price, $amount)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;    
}