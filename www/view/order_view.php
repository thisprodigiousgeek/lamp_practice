<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>

  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>

<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>＜購入履歴＞</h1>
  <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table>
      <thead>
        <tr>
          <th>注文番号</th>
          <th>購入日時（新着順）</th>
          <th>合計金額</th>
          <th>購入明細</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach($orders as $order){ ?>
        <tr>
          <td><?php print(h($order['order_id'])); ?></td>
          <td><?php print(h($order['order_datetime'])); ?></td>
          <td><?php print(h($order['total'])); ?></td>
          <td>
            <form method="post" action="statement.php">
              <input type="submit" value="明細">
              <input type="hidden" name="order_id" value=<?php print(h($order['order_id'])); ?>>
              <input type="hidden" name="order_datetime" value=<?php print(h($order['order_datetime'])); ?>>
              <input type="hidden" name="total" value=<?php print(h($order['total'])); ?>>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>

    </table> 
</body>