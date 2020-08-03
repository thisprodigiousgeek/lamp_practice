<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_orders($db){
    $sql = "
      SELECT
        order_id, 
        user_id,
        created
      FROM
        orders
    ";
  
    return fetch_all_query($db, $sql);
}

function get_order_items($db){
    return get_orders($db);
}

?>