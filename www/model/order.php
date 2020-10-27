<?php 
//それぞれphpファイルの読み込み
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//データベースの関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';

//購入履歴・購入明細トランザクション
function regist_order_transaction($db, $user_id, $total_price, $carts){
  $db->beginTransaction();
  if(insert_order($db, $user_id, $total_price)){
    $order_history_id = $db->lastInsertId('order_history_id');
    foreach($carts as $read){
      if(insert_order_details($db, $order_history_id, $read['item_id'], $read['price'], $read['amount']) === false){
        $db->rollback();
        set_error('購入明細エラー');
        return false;
      }
    }
    $db->commit();
    return true;
  }
  $db->rollback();
  set_error('購入履歴エラー');
  return false;
}

//購入履歴挿入SQL文
function insert_order($db, $user_id, $total_price){
  $sql = "
    INSERT INTO
      order_history(
        user_id,
        total_price
      )
    VALUES(:user_id, :total_price)
  ";

  $array=array(':user_id'=>$user_id, ':total_price'=>$total_price);
  return execute_query($db, $sql, $array);
}

//購入明細挿入SQL
function insert_order_details($db, $order_history_id, $item_id, $price, $amount){
  $sql = "
    INSERT INTO
      order_details(
        order_history_id,
        item_id,
        price,
        amount
        )
    VALUES(:order_history_id, :item_id, :price, :amount)
  ";
  
  $array=array(':order_history_id'=>$order_history_id, ':item_id'=>$item_id, ':price'=>$price, ':amount'=>$amount);
  return execute_query($db, $sql, $array);
}

//ユーザーごとの注文履歴取得SQL文
function get_user_order_history($db, $user_id){
  $sql = "
    SELECT
      order_history_id, 
      user_id,
      total_price,
      created
    FROM
      order_history
    WHERE
      user_id = :user_id
    ORDER BY
      created DESC
  ";
  $array=array(':user_id'=>$user_id);
  return fetch_all_query($db, $sql, $array);
}

//ユーザーごとの注文履歴取得SQL文
function get_id_order_history($db, $order_history_id){
  $sql = "
    SELECT
      order_history_id, 
      user_id,
      total_price,
      created
    FROM
      order_history
    WHERE
      order_history_id = :order_history_id
    ORDER BY
      created DESC
  ";
  $array=array(':order_history_id'=>$order_history_id);
  return fetch_all_query($db, $sql, $array);
}

//全ユーザーの注文履歴取得SQL文
function get_alluser_order_history($db){
  $sql = "
    SELECT
      order_history_id, 
      user_id,
      total_price,
      created
    FROM
      order_history
    ORDER BY
      created DESC
  ";
  $array=array();
  return fetch_all_query($db, $sql, $array);
}

//ユーザーごとの注文明細取得SQL文
function get_user_order_details($db, $order_history_id){
  $sql = "
    SELECT
      order_details.order_details_id, 
      order_details.item_id,
      order_details.price,
      order_details.amount,
      order_details.created,
      items.name
    FROM
      order_details
    JOIN
      items
    ON
      order_details.item_id = items.item_id
    WHERE
      order_details.order_history_id = :order_history_id
    ORDER BY
      created DESC
  ";
  $array=array(':order_history_id'=>$order_history_id);
  return fetch_all_query($db, $sql, $array);
}

//全ユーザーの注文明細取得SQL文
function get_alluser_order_details($db){
  $sql = "
    SELECT
      order_details.order_details_id, 
      order_details.item_id,
      order_details.price,
      order_details.amount,
      order_details.created,
      items.name
    FROM
      order_details
    JOIN
      items
    ON
      order_details.item_id = items.item_id
    ORDER BY
      created DESC
  ";
  $array=array();
  return fetch_all_query($db, $sql, $array);
}
