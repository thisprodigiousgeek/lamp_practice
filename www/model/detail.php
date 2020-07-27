<?php 

function insert_detail($db, $item_id, $order_id, $amount, $price){
    $sql = "
        INSERT INTO
            detail(
                item_id,
                order_id,
                amount,
                price
            )
        VALUES(?, ?, ?, ?);
    ";

    return execute_query($db, $sql, array($item_id, $order_id, $amount, $price));
}
