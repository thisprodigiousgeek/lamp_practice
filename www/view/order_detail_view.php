<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細画面</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($order) > 0){ ?>
      <table class="table table-bordered">
          <tr>
              <td>注文番号</td>
              <td>購入日時</td>
              <td>合計金額</td>
          </tr>
          <tr>
              <td><?php print h($order['order_id']); ?></td>
              <td><?php print h($order['order_date']); ?></td>
              <td><?php print number_format($order['total_price']); ?>円</td>
          </tr>
      </table>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($details as $detail) { ?>
          <tr>
            <td><?php print h($detail['item_name']); ?></td>
            <td><?php print number_format($detail['price']); ?>円</td>
            <td><?php print h($detail['amount']); ?></td>
            <td><?php print number_format($detail['total_price']); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴がありません。</p>
    <?php } ?> 
  </div>
</body>
</html>