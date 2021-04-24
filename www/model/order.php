<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ordersテーブルに新規登録
function insert_orders($db,$user_id){
    $sql ="INSERT INTO orders(user_id) VALUES(?);";

    execute_query($db,$sql,[$user_id]);
    return $db->lastInsertId();
}

//order_detailテーブルに新規登録
function insert_order_details($db,$order_id,$item_id,$product_price,$quantity){
    $sql ="INSERT INTO order_details(order_id, item_id, product_price, quantity) VALUES(?,?,?,?);";

    return execute_query($db,$sql,[$order_id,$item_id,$product_price,$quantity]);
}


//ordersとorder_detailsテーブルに追加
function order_transaction($db,$user_id,$carts){
    $db->beginTransaction();
    try{
        $order_id = insert_orders($db,$user_id);
        foreach($carts as $value){
            insert_order_details($db,$order_id,$value['item_id'],$value['price'],$value['amount']);
        }
        $db->commit();
        return true;
    }catch(PDOException $e){
        set_error('データ取得に失敗しました。');
        $db->rollback();
        return false;
    }
}

//指定のユーザーの購入履歴を取得
function get_orders_user_id($db,$user_id){
    $sql ="SELECT
    orders.order_id,order_date,SUM(order_details.product_price*order_details.quantity) as total
    FROM
    orders
    JOIN
    order_details
    ON
    orders.order_id=order_details.order_id
    WHERE
    user_id = ?
    GROUP BY
    order_id";

    $orders = fetch_all_query($db,$sql,[$user_id]);
    return array_reverse($orders);
}

//全ての購入履歴を取得（adminユーザー）
function get_orders($db){
    $sql ="SELECT
    orders.order_id,order_date,SUM(order_details.product_price*order_details.quantity) as total
    FROM
    orders
    JOIN
    order_details
    ON
    orders.order_id=order_details.order_id
    GROUP BY
    order_id";

    $orders = fetch_all_query($db,$sql);
    return array_reverse($orders);
}

//adminユーザーなら全ての履歴を見れる
function orders_check_user($db,$user_id){
    if($user_id === 4){
        return get_orders($db);
    }else{
        return get_orders_user_id($db,$user_id);
    }
}

//詳細情報を取得
function get_order_detail($db,$order_id){
    $sql ="SELECT items.name,order_details.product_price,order_details.quantity,(order_details.product_price*order_details.quantity) as total
    FROM order_details 
    JOIN items 
    ON items.item_id = order_details.item_id 
    WHERE order_details.order_id = ?";

    return fetch_all_query($db,$sql,[$order_id]);
}