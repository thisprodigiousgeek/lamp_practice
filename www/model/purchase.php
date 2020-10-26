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
  ";
  $params = array(':user_id' => $user_id);

  return fetch_all_query($db, $sql, $params);
}

function get_details($db, $order_id) {
  $sql = "
    SELECT
      items.name,
      details.price,
      details.amount,
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