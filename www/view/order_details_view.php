<?php
header('X-FRAME-OPTIONS: DENY');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(h(STYLESHEET_PATH . 'order.css')); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(is_admin($user) === false){ ?>
      <?php foreach($order_history as $value){ ?>
        <p class="text-right">注文番号: <?php print h($value['order_history_id']); ?></p>
        <p class="text-right">合計金額: <?php print h(number_format($value['total_price'])); ?>円</p>
        <p class="text-right">購入日時: <?php print h($value['created']); ?></p>
      <?php } ?>
    <?php } ?>


    <?php if(count($order) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order as $read){ ?>
          <tr>
            <td><?php print(h($read['name'])); ?></td>
            <td><?php print(h(number_format($read['price']))); ?>円</td>
            <td><?php print(h($read['amount'])); ?></td>
            <td><?php print(h(number_format($read['price']*$read['amount']))); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>