<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ordersテーブルに新規登録
function insert_orders($db,$user_id){
    $sql ="INSERT INTO orders(user_id) VALUES(?);";

    return execute_query($db,$sql,[$user_id]);
}

//order_detailテーブルに新規登録
function insert_order_details($db,$order_id,$item_id,$product_price,$quantity){
    $sql ="INSERT INTO order_details(order_id, item_id, product_price, quantity) VALUES(?,?,?,?);";

    return execute_query($db,$sql,[$order_id,$item_id,$product_price,$quantity]);
}

//直前に追加したorder_idを取得
function get_orders_order_id($db){
    $sql ="SELECT order_id FROM orders";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $order = $stmt->fetchALL();
    $order =  end($order);
    return $order['order_id'];
}

function order_transaction($db,$user_id,$carts){
    $db->beginTransaction();
    try{
        insert_orders($db,$user_id);
        $order_id = get_orders_order_id($db);
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

//指定のユーザーの購入履歴を取得（ordersテーブル）
function get_orders_user_id($db,$user_id){
    $sql ="SELECT order_id,order_date FROM orders WHERE user_id = ?";

    $orders = fetch_all_query($db,$sql,[$user_id]);
    return array_reverse($orders);
}

//全ての購入履歴を取得（adminユーザー）
function get_orders($db){
    $sql ="SELECT order_id,order_date FROM orders";

    $orders = fetch_all_query($db,$sql,[$user_id]);
    return array_reverse($orders);
}

//adminユーザーなら全ての履歴を見れる
function orders_check_user(){
    if($user_id === 4){
        return get_orders($db);
    }else{
        return get_orders_user_id($db,$user_id);
    }
}


















//カートの合計金額
// function sum_carts($carts){
//     $total_price = 0;
//     foreach($carts as $cart){
//       $total_price += $cart['price'] * $cart['amount'];
//     }
//     return $total_price;
//   }