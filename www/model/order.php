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

function get_all_orders($db){
  $sql = "
    SELECT
      orders.order_id,
      orders.user_id,
      orders.created,
      sum(order_details.purchase_price * order_details.quantity) AS total_price
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    GROUP BY
      orders.order_id
    ORDER BY
      orders.created DESC
  ";
  return fetch_all_query($db, $sql, $params);
}

function get_user_orders($db, $user_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.user_id,
      orders.created,
      sum(order_details.purchase_price * order_details.quantity) AS total_price
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    GROUP BY
      orders.order_id
    HAVING
      orders.user_id = ?
    ORDER BY
      orders.created DESC
  ";
  $params = array($user_id);
  return fetch_all_query($db, $sql, $params);
}

function get_orders($db, $order_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.user_id,
      orders.created,
      sum(order_details.purchase_price * order_details.quantity) AS total_price
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    GROUP BY
      orders.order_id
    HAVING
      orders.order_id = ?
  ";
  $params = array($order_id);
  return fetch_query($db, $sql, $params);
}

function get_order_details($db, $order_id){
  $sql = "
    SELECT
      items.name,
      order_details.purchase_price,
      order_details.quantity,
      order_details.purchase_price * order_details.quantity AS sub_total
    FROM
      order_details
    JOIN
      items
    ON
      order_details.item_id = items.item_id
    WHERE
      order_details.order_id = ?
  ";
  $params = array($order_id);
  return fetch_all_query($db, $sql, $params);
}