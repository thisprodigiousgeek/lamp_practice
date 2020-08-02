<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>

<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>＜購入明細＞</h1>
  <p>
  注文番号：<?php print $order_id ?>　
  購入日時：<?php print $order_datetime ?>　
  合計金額：<?php print $total.'円' ?>　
  </p>
  <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table>
      <thead>
        <tr>
          <th>商品名</th>
          <th>価　格</th>
          <th>購入数</th>
          <th>小　計</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($statements as $statement){ ?>
        <tr>
          <td><?php print(h($statement['item_name'])); ?></td>
          <td><?php print(h($statement['price'])); ?></td>
          <td><?php print(h($statement['amount'])); ?></td>
          <td><?php print(number_format(h($statement['price'] )* h($statement['amount']))); ?>円</td>
        </tr>
      </tbody>
        <?php } ?>
    </table> 
</body>