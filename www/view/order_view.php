<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?> <!--6.25???????????????-->
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>
<body>
  <h1>購入履歴</h1>
  <div class="">

    <?php include VIEW_PATH . 'templates/messages.php'; ?> <!--6.25???????????????-->

      <table>
        <thead> <!--??????-->
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
      </table>
      
  </div>
</body>