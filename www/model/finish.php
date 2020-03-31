<?php

//購入履歴への新規登録
function add_history($db, $user_id)
{
        $sql = 'INSERT INTO purchase_history (`user_id`,  `create`, `update`)
                VALUES(?, now(), now());';
        execute_query($db, $sql, array($user_id));
}

//購入明細への新規登録
function add_detail($db, $rows)
{
        //buy_idを取得
        $id = $db->lastInsertId();
        //購入明細の更新
        foreach ($rows as $row) {
            $sql = 'INSERT INTO purchase_detail (buy_id, item_id, amount, price)
                VALUES(?, ?, ?, ?);';
            execute_query($db, $sql, array($id, $row['item_id'], $row['amount'], $row['price']));
        }
}
