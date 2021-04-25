<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <?php if(count($details) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print(h($order['order_id'])); ?></td>
            <td><?php print(h($order['created'])); ?></td>
            <td><?php print(number_format(sum_carts($details))); ?>円</td>
          </tr>
      </tbody>
      </table>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($details as $details){ ?>
          <tr>
            <td><img src="<?php print(IMAGE_PATH . $details['image']);?>" class="item_image"></td>
            <td><?php print(h($details['name'])); ?></td>
            <td><?php print(number_format($details['price'])); ?>円</td>
            <td><?php print(number_format($details['amount'])); ?>個</td>
            <td><?php print(number_format($details['price'] * $details['amount'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <p>購入明細がありません。</p>
    <?php } ?> 
  </div>
  
</body>
</html>