<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($purchased_history) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>発注番号</th>
            <th>購入日時</th>
            <th>購入合計</th>
            <th>購入詳細</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach($purchased_history as $value){ ?>
          <tr>
            <td>No.<?php print $value['purchased_history_id']; ?></td>
            <td><?php print $value['created']; ?></td>
            <td>計:<?php print number_format($value['totalprice']); ?>円</td>
            <td>
              <form action="details.php" method="get">
                <input type="submit" value="詳細表示">
                <input type="hidden" name="details_id" value="<?php print $value['purchased_history_id']; ?>">
              </form> 
            </td>
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