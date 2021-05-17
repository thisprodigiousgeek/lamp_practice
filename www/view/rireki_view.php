<!--
    CREATE history (
        order_id INT(11) AUTO_INCREMENT,
        user_id INT(11),
        created datetime, 
        primary key(order_id),
    );
    
-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <meta charset="UTF-8">
    <title>購入履歴</title>
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?> <!--header-->
    <h1>購入履歴</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
      <table class="table table-bordered">
          <tr>
              <th>注文番号</th>
              <th>購入日時</th>
              <th>合計金額</th>
          </tr>
      </table>        
</body>
</html>