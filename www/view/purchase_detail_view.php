<?php header("X-FRAME-OPTIONS: DENY");?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(h(STYLESHEET_PATH . 'purchase.css')); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <ul>
      <li>注文番号:<?php print h($order_id);?></li>
      <li>購入日時:<?php print h($datetime);?></li>
      <li>合計金額:<?php print h($total);?></li>
    </ul>

    <table border=4>
      <tr></tr>
      <tr>
        <th>商品名</th><th>価格</th><th>購入数</th><th>小計</th>
      </tr>
      
      <?php foreach($details as $row){?>
      <tr>
        <td><?php print h($row['name']);?></td>
        <td><?php print h($row['price']);?></td>
        <td><?php print h($row['amount']);?></td>
        <td><?php print h($row['price'] * $row['amount']);?></td>
      </tr>
      <?php }?>
    </table>
</body>
</html>