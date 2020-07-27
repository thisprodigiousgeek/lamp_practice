<?php 

function insert_order($db, $user_id, $order_datetime){
    $sql = "
        INSERT INTO
            `order`(
                user_id,
                order_datetime
            )
        VALUES(?, ?);
    ";

    return execute_query($db, $sql, array($user_id, $order_datetime));
}