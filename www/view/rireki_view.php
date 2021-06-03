<!--
    購入履歴テーブル
    CREATE TABLE histories (
        history_id INT(11) AUTO_INCREMENT, //注文番号
        user_id INT(11),
        created datetime, 
        primary key(history_id)
    );

    購入明細テーブル
    CREATE TABLE details (
        detail_id INT(11) AUTO_INCREMENT,
        history_id INT(11), //historiesと同期させる
        item_id INT(11),
        price INT(11),　//購入時の商品価格
        amount INT(11),
        primary key(detail_id)
    );
    
-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入履歴</title>
</head>
<body>
    <h1>購入履歴</h1>
      <table>
          <tr>
              <th>注文番号</th>
              <th>購入日時</th>
              <th>合計金額</th>
          </tr>
      </table> 
        
</body>
</html>