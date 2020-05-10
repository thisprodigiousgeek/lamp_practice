<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_purchase_history($db, $user_id){
    $sql = '
        SELECT
            purchase_history.order_id, 
            purchase_history.user_id,
            purchase_history.order_date,
            sum(purchase_details.purchase_price) as purchase_sum
        FROM
            purchase_history INNER JOIN purchase_details
        ON 
            purchase_history.order_id = purchase_details.order_id';
        if($user_id !== 4){
            $sql.=' WHERE purchase_history.user_id = ?';
        }
        $sql.=' GROUP BY purchase_history.order_id
        ORDER BY purchase_history.order_id DESC
    ';
    if($user_id !== 4){
        return fetch_all_query($db, $sql, array($user_id) );
    }else{
        return fetch_all_query($db, $sql);
    }
}

function get_purchase_details($db, $order_id){
    $sql = '
        SELECT 
            items.name,
            purchase_details.purchase_price,
            purchase_details.item_amount,
            purchase_history.order_date,
            purchase_history.user_id
        FROM 
            (purchase_details INNER JOIN purchase_history ON purchase_details.order_id = purchase_history.order_id)
        INNER JOIN items ON purchase_details.item_id = items.item_id
        WHERE
            purchase_details.order_id = ?  
        ';
    return fetch_all_query($db, $sql, array($order_id));
}

function check_user_id($user_id, $check_id, $data){
    if($user_id === 4 || $user_id === $check_id){
        return change_htmlsp_array($data);
    }else{
        set_error('不正なアクセスです');
    }
}

function sum_purchase($items){
    $sum = 0;
    foreach($items as $item){
        $sum += ($item['purchase_price']*$item['item_amount']);
    }
    return $sum;
}

function change_timestamp($items){
    foreach($items as $key => $value){
        $items[$key]['order_date'] = date('Y年n月j日 H時i分s秒', strtotime($value['order_date']));
    }
    return $items;
}