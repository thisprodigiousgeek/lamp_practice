<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_history($db, $user_id) {
  $sql = "
    SELECT
      order_id,
      purchase_date
    FROM
      history
    WHERE
      user_id = :user_id
    ORDER BY
      order_id DESC
  ";
  $params = array(':user_id' => $user_id);

  return fetch_all_query($db, $sql, $params);
}

function get_history_details($db, $user_id) {
  $sql = "
    SELECT
      history.order_id,
      details.price,
      details.amount
    FROM
      history
    JOIN
      details
    ON
      history.order_id = details.order_id
    WHERE
      user_id = :user_id
    ORDER BY
      order_id DESC
  ";
  $params = array(':user_id' => $user_id);

  return fetch_all_query($db, $sql, $params);
}

function get_details($db, $order_id) {
  $sql = "
    SELECT
      items.name,
      details.price,
      details.amount
    FROM
      details
    JOIN
      items
    ON
      details.item_id = items.item_id
    WHERE
      details.order_id = :order_id
  ";
  $params = array(':order_id' => $order_id);

  return fetch_all_query($db, $sql, $params);
}

function get_purchase_date($db, $order_id) {
  $sql = "
    SELECT
      purchase_date
    FROM 
      history
    WHERE
      order_id = :order_id
  ";
  $params = array(':order_id' => $order_id);

  return fetch_query($db, $sql, $params);
}

function get_all_history($db) {
  $sql = "
    SELECT
      history.order_id,
      history.purchase_date,
      details.price,
      details.amount
    FROM
      history
    JOIN
      details
    ON
      history.order_id = details.order_id
    ORDER BY
      order_id DESC
  ";
  return fetch_all_query($db, $sql, $params);
}

function get_all_history_details($db) {
  $sql = "
    SELECT
      history.order_id,
      details.price,
      details.amount
    FROM
      history
    JOIN
      details
    ON
      history.order_id = details.order_id
    ORDER BY
      order_id DESC
  ";
  return fetch_all_query($db, $sql, $params);
}

function sum_purchase($details){
  $total_price = 0;
  foreach($details as $detail){
    $total_price += $detail['price'] * $detail['amount'];
  }
  return $total_price;
}

function admin_history($db) {
  $sql = "
    SELECT
      order_id,
      purchase_date
    FROM
      history
    ORDER BY
      order_id DESC
  ";
  return fetch_all_query($db, $sql, $params);
}

function admin_history_sum($db) {
  $sql = "
    SELECT
      SUM(price * amount),
      order_id
    FROM
      details
    GROUP BY
      order_id
  ";
  return fetch_all_query($db, $sql, $params);
}