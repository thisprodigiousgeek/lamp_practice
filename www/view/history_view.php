<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入詳細</title>
</head>
<body>
  <?php
  include VIEW_PATH .'templates/header_logined.php';
  ?>
  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>注文番号</th>
        <th>注文日時</th>
        <th>該当の注文の合計金額</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($history_data as $row) { ?>
      <tr>
        <td><?php print(h($row['history_id'])); ?></td>
        <td><?php print(h($row['create_datetime'])); ?></td>
        <td><?php print(number_format($row['total_price'])); ?>円</td>
        <td>
          <form
          method="post"
          action="history_detail.php"
          >
          <input type="submit" value="購入明細表示" class="btn btn-primary">
          <input type="hidden" name="history_id" value="<?php print(h($row['history_id'])); ?>">
          <input type="hidden" name="purchase_date" value="<?php print(h($row['create_datetime']));?>">
          <input type="hidden" name="total_price" value="<?php print(number_format($row['total_price']));?>">
          <input type="hidden" name="token" value="<?php print($token); ?>">
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </div>

</body>
</html>