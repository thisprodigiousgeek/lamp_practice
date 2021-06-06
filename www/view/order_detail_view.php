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
  <h1>購入詳細</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>注文番号: <?php print($order_id)?></th>
              <th>購入日時: <?php print($order_date)?></th>
              <th>合計金額: <?php print($total)?></th>
            </tr>
          </thead>
        </table>

        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>商品名</th>
              <th>商品価格</th>
              <th>購入数</th>
              <th>小計</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($order_details as $detail){ ?>
            <tr>
              <td><?php print($detail['name'])?></td>
              <td><?php print($detail['product_price'])?></td>
              <td><?php print($detail['quantity'])?></td>
              <td><?php print($detail['total'])?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
  </div>
  
</body>
</html>