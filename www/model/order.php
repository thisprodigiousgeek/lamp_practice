<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

/**
 * クエリを実行し、ユーザーの注文情報を取得
 * @param obj $db dbハンドル
 * @param str $user_id ユーザid
 * @return array|bool 注文歴歴|false
 */
function get_user_orders($db, $user_id){
  $sql = "
    SELECT
      order_id,
      created
    FROM
      orders
    WHERE
      user_id = ?
  ";
  return fetch_all_query($db, $sql, array($user_id));
}
/**
 * クエリを実行し、注文番号から注文情報を取得
 * @param obj $db dbハンドル
 * @param str $order_id 注文番号
 * @return array|bool 注文歴歴|false
 */
function get_order($db, $order_id){
  $sql = "
    SELECT
      order_id,
      created
    FROM
      orders
    WHERE
      order_id = ?
  ";
  return fetch_query($db, $sql, array($order_id));
}
/**
 * クエリを実行し、注文番号から注文明細情報を取得
 * @param obj $db dbハンドル
 * @param str $order_id 注文番号
 * @return array|bool カート情報|false
 */
function get_order_details($db, $order_id){
  $sql = "
    SELECT
      details.details_id,
      details.order_id,
      details.price,
      details.amount,
      items.item_id,
      items.name,
      items.image
    FROM
      details
    JOIN
      items
    ON
      details.item_id = items.item_id
    WHERE
      details.order_id = ?
  ";
  return fetch_all_query($db, $sql, array($order_id));
}