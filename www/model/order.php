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

function get_order_items($db){
    return get_orders($db);
}



?>