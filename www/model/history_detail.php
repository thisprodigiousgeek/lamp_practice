<?php
function insert_history_detail ($db, $item_id, $history_id, $purchased_price, $amount) {
    $sql = 'INSERT INTO history_details (
        item_id, 
        history_id, 
        purchased_price,
        amount
    )VALUES(
        :item_id,
        :history_id, 
        :purchased_price,
        :amount
    )';

    $params = array(
        ':item_id' => $item_id,
        ':history_id' => $history_id,
        ':purchased_price'  => $purchased_price,
        ':amount'  => $amount
    );


    return execute_query($db, $sql, $params);
}
