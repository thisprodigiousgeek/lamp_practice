<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function insert_histories($db, $user_id, $carts){
    //historiesテーブルに挿入する
    insert_history($db, $user_id);
    $history_id = $db->lastInsertId();
    //detailテーブルに挿入する
    insert_detail($db, $carts, $history_id);
}

function insert_history($db, $user_id){
    $created=date('Y-m-d H:i:s');
    $sql='INSERT INTO histories (user_id, created) VALUES (?, ?)';
    return execute_query($db, $sql, array($user_id, $created));
}


function insert_detail($db, $carts, $history_id){
    foreach($carts as $value){
        $sql='INSERT INTO details (history_id, item_id, price, amount) VALUE (?, ?, ?, ?)';
        execute_query($db, $sql, array($history_id, $value['item_id'], $value['price'], $value['amount']));
    }
}
