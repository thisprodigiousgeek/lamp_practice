<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php';?>
  <title>購入詳細</title>
</head>
<body>
  <?php
  include VIEW_PATH . 'templates/header_logined.php';
  ?>
  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <div id="history-data">
      <p>注文番号：<?php print(h($history_id));?></p>
      <p>購入日時：<?php print(h($purchase_date));?></p>
      <p>合計金額：<?php print(number_format($total_price));?>円</p>
    </div>
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>商品名</th>
          <th>購入時の商品価格</th>
          <th>購入数</th>
          <th>小計</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($history_detail_data as $row) { ?>
        <tr>
          <td><?php print(h($row['name']));?></td>
          <td><?php print(number_format($row['price']));?></td>
          <td><?php print(h($row['amount']));?></td>
          <td><?php print(number_format($row['sub_total_price']));?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</body>
</html>