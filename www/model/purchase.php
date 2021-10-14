<?php

require_once MODEL_PATH.'db.php';
require_once MODEL_PATH.'functions.php';

function insert_purchase_history($db,$user_id){

    $sql = 'INSERT INTO  purchase_history(user_id)
            VALUES (:user_id)';

    return execute_query($db,$sql,array(':user_id'=>$user_id));

}

function insert_purchase_detail($db,$order_id,$item_id,$amount){

    $sql = 'INSERT INTO purchase_detail(order_id,item_id,amount)
            VALUES(:order_id,:item_id,:amount)';

    return execute_query($db,$sql,array(':order_id'=>$order_id,':item_id'=>$item_id,':amount'=>$amount));

}


