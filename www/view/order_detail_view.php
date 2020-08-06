<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入明細</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
          <tr>
            <td><?php print ($order['order_id']); ?></td>
            <td><?php print ($order['created']); ?></td>
            <td><?php print number_format($order['total']); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>

    <?php if(count($orders) > 0 && count($order_details) > 0){ ?>
      <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_details as $order_detail){ ?>
          <tr>
            <td><?php print h($order_detail['name']); ?></td>
            <td><?php print number_format($order_detail['order_price']); ?>円</td>
            <td><?php print ($order_detail['order_amount']); ?></td>
            <td><?php print number_format($order_detail['order_price']*$order_detail['order_amount']); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>