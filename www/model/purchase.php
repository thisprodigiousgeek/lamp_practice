<?php

require_once MODEL_PATH.'db.php';
require_once MODEL_PATH.'functions.php';

function insert_purchase_history($db,$user_id){

    $sql = 'INSERT INTO  purchase_history(user_id)
            VALUES (:user_id)';

    return execute_query($db,$sql,array(':user_id'=>$user_id));

}

function insert_purchase_detail($db,$order_id,$item_id,$price,$amount){

    $sql = 'INSERT INTO purchase_detail(order_id,item_id,price,amount)
            VALUES(:order_id,:item_id,:price,:amount)';

    return execute_query($db,$sql,array(':order_id'=>$order_id,':item_id'=>$item_id,'price'=>$price,':amount'=>$amount));

}

function purchase_detail($db,$carts,$order_id){

    foreach($carts as $cart){

        if(insert_purchase_detail(
            $db, 
            $order_id,
            $cart['item_id'],
            $cart['price'],
            $cart['amount']
          ) === false){
          set_error($cart['name'] . 'の購入明細の登録に失敗しました。');
          return false;
        }

    }
    return true;
}

//adminユーザーの場合と通常のユーザーの場合の2つselect文を書く

//通常ユーザーの場合
function get_purchase_history($db,$user_id){

    //order_idごとに
    $sql = "SELECT
              purchase_history.order_id,
              purchase_history.datetime,
              SUM(purchase_detail.price * purchase_detail.amount) AS total
            FROM
              purchase_history
            JOIN
              purchase_detail
            ON
              purchase_history.order_id = purchase_detail.order_id
            WHERE
              user_id = :user_id
            GROUP BY
              order_id
            ORDER BY datetime DESC";

              return fetch_all_query($db,$sql,array(':user_id'=>$user_id));
          }

//adminユーザーの場合
function get_purchase_histories($db){

    $sql = 'SELECT 
              purchase_history.order_id,
              purchase_history.datetime,
              SUM(purchase_detail.price * purchase_detail.amount) AS total
            FROM   
              purchase_history
            join   
              purchase_detail
            ON     
              purchase_history.order_id = purchase_detail.order_id
            GROUP BY 
              order_id
            ORDER BY 
              datetime DESC';

    return fetch_all_query($db,$sql);

}


//購入明細取得


//通常のユーザー
function get_purchase_details($db,$order_id){

    $sql = 'SELECT
              items.name,
              purchase_detail.item_id,
              purchase_detail.price,
              purchase_detail.amount
            FROM
              purchase_detail
            JOIN
              items
            ON
              purchase_detail.item_id = items.item_id
            WHERE
              order_id = :order_id
            ';

    return fetch_all_query($db,$sql,array(':order_id'=>$order_id));
}



