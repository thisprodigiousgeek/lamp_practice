<?php
//購入テーブルのデータ取得
function get_purchase_data($db, $user_name, $user_id)
{
    $sql =
        "SELECT
            purchase_history.buy_id,
            purchase_history.update,
            sum(purchase_detail.amount * purchase_detail.price) as total_price
        FROM
            purchase_history
        INNER JOIN
            purchase_detail
        ON
            purchase_history.buy_id = purchase_detail.buy_id
        INNER JOIN
        	items
         ON
         	purchase_detail.item_id = items.item_id
        WHERE
            CASE WHEN
                ? = 'admin' THEN 1
            ELSE
                purchase_history.user_id = ?
            END
        GROUP BY
        	purchase_history.buy_id;";
    $stmt = $db->prepare($sql);
    return fetch_all_query($db, $sql, array($user_name, $user_id));
}

// //合計金額の算出
// function calculation_total_price($db, $rows)
// {
//     $total = array();
//     foreach ($rows as $row) {
//         $sum = 0;
//         //buy_id共通の商品絞り込み
//         $sql =
//             "SELECT
//                 item_id,
//                 amount
//             FROM
//                 purchase_detail
//             WHERE
//                 buy_id = ?;";
//         $data = fetch_All_query($db, $sql, array($row['buy_id']));

//         foreach ($data as $dt) {
//             //商品の値段を取得
//             $sql =
//                 "SELECT
//                     price
//                 FROM
//                     items
//                 WHERE
//                     item_id = ?;";
//             $price = fetch_query($db, $sql, array($dt['item_id']));

//             //購入した商品ごとの小計を計算して$sumに加算 = 合計金額の算出
//             $sum += $price['price'] * $dt['amount'];
//         }
//     //計算した合計金額を配列に格納
//     $total[$row['buy_id']] = $sum;
//     }
//     return $total;
// }
