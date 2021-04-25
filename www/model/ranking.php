<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_ranking($db){
    $sql = "SELECT items.name,items.image,SUM(quantity) as total
    FROM order_details
    JOIN items
    ON items.item_id = order_details.item_id
    GROUP BY order_details.item_id
    ORDER BY total DESC
    LIMIT 3";

    return fetch_all_query($db,$sql); 
}