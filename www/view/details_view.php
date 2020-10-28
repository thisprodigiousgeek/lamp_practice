<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入詳細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入詳細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>発注番号</th>
            <th>購入日時</th>
            <th>購入合計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($purchased_history as $value){ ?>
              <tr>
                <td>No.<?php print $value['purchased_history_id']; ?></td>
                <td><?php print $value['created']; ?></td>
                <td><?php print number_format($value['totalprice']); ?>円</td>
              </tr>
            <?php } ?>
        </tbody>
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
          <?php foreach($details as $value){ ?>
              <tr>
                <td><?php print $value['name']; ?></td>
                <td><?php print number_format($value['price']); ?>円</td>
                <td><?php print $value['amount']; ?>個</td>
                <td><?php print number_format($value['price'] * $value['amount']); ?>円</td>
              </tr>
            <?php } ?>
        </tbody>
        </table>
  </div>
</body>
</html>