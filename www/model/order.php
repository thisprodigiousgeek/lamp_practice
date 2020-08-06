<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_orders($db){
    $sql = "
      SELECT
        orders.order_id, 
        orders.user_id,
        orders.created,
        SUM(order_price*order_amount) AS total
      FROM
        orders
      INNER JOIN 
        order_details
      ON 
        orders.order_id = order_details.order_id
      GROUP BY
        orders.order_id
    ";
  
    return fetch_all_query($db, $sql);
}

function get_user_orders($db, $normal_user){
    $sql = "
      SELECT
        orders.order_id, 
        orders.user_id,
        orders.created,
        SUM(order_price*order_amount) AS total
      FROM
        orders
      INNER JOIN 
        order_details
      ON 
        orders.order_id = order_details.order_id
      WHERE
        orders.user_id = :user_id
      GROUP BY
        orders.order_id
    ";
  
    return fetch_all_query($db, $sql, array(':user_id' => $normal_user));
}

function get_user_order_totals($db, $order_id){
    $sql = "
      SELECT
        orders.order_id, 
        orders.created,
        SUM(order_price*order_amount) AS total
      FROM
        orders
      INNER JOIN 
        order_details
      ON 
        orders.order_id = order_details.order_id
      WHERE
        orders.order_id = :order_id
      GROUP BY
        orders.order_id
    ";
  
    return fetch_all_query($db, $sql, array(':order_id' => $order_id));
}

function get_user_order_details($db, $order_id){
    $sql = "
      SELECT
        order_details.order_amount,
        order_details.order_price,
        items.name
      FROM
        order_details
      INNER JOIN 
        items
      ON 
        order_details.item_id = items.item_id
      WHERE
        order_details.order_id = :order_id
    ";
  
    return fetch_all_query($db, $sql, array(':order_id' => $order_id));
}

?>