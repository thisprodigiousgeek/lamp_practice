<?php
require_once '../conf/const.php';
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

function price_low($db){
    $sql="SELECT *
    FROM
    items
    WHERE
    status = 1
    ORDER BY 
    price";

    return fetch_all_query($db, $sql); 
}

function price_high($db){
    $sql="SELECT *
    FROM
    items
    WHERE
    status = 1
    ORDER BY 
    price DESC";

    return fetch_all_query($db, $sql);
}

function new_item($db){
    $sql="SELECT *
    FROM
    items
    WHERE
    status = 1
    ORDER BY 
    created DESC";

    return fetch_all_query($db, $sql);
}

function get_sort($db,$key){
    if($key === '新着順'){
        return new_item($db);
    }elseif($key === '安い順'){
        return price_low($db);
    }elseif($key === '高い順'){
        return price_high($db);
    }else{
        return false;
    }
}