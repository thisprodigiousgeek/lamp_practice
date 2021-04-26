<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>注文履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>注文履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文金額</th>
            <th>購入明細表示</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach(array_reverse($orders) as $order){ ?>
          <tr>
            <td><?php print(h($order['order_id'])); ?></td>
            <td><?php print(h($order['created'])); ?></td>
            <td><?php print(number_format($order['total'])); ?>円</td>
            <td>
              <form method="GET" action="details.php">
                <input type="submit" value="購入明細表示" class="btn btn-secondary">
                <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>注文履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>