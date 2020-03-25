<?php

//購入管理テーブル２種に必要な変数を取得
// function get_variable($db, $user)
// {
//     $sql = 'SELECT user_id FROM users WHERE name = ?;';
//     $stmt = $db->prepare($sql);
//     $stmt->bindValue(1, $user['name'], PDO::PARAM_STR);
//     $user_id = $stmt->execute();


//     $sql = "SELECT * FROM carts WHERE user_id = ? ;";
//     $rows = fetch_all_query($db, $sql, $user_id);

//     return array($user_id);
// }

//購入履歴への新規登録
function add_history($db, $user_id)
{
    try {
        $sql = 'INSERT INTO purchase_history (`user_id`, `create`, `update`)
                VALUES(?, now(), now());';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        set_error('購入履歴の更新に失敗しました。');
    }
    return false;
}

//購入明細への新規登録
function add_detail($db, $rows)
{
    try {
        //buy_idを取得
        $id = $db->lastInsertId();
        //購入明細の更新
        foreach ($rows as $row) {
            $sql = 'INSERT INTO purchase_detail (buy_id, item_id, amount)
                VALUES(?, ?, ?);';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->bindValue(2, $row['item_id'], PDO::PARAM_INT);
            $stmt->bindValue(3, $row['amount'], PDO::PARAM_STR);
            $stmt->execute();
        }
        return true;
    } catch (PDOException $e) {
        set_error('購入明細の更新に失敗しました。');
    }
    return false;
}
