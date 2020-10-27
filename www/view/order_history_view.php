<?php
header('X-FRAME-OPTIONS: DENY');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(h(STYLESHEET_PATH . 'order.css')); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($order) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>合計金額</th>
            <th>購入日</th>
            <th>明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order as $read){ ?>
          <tr>
            <td><?php print(h($read['order_history_id'])); ?></td>
            <td><?php print(h(number_format($read['total_price']))); ?>円</td>
            <td><?php print(h($read['created'])); ?></td>
            <td>
              <form method="get" action="order_details.php">
                <input type="submit" value="購入明細" class="btn details">
                <input type="hidden" name="order_history_id" value="<?php print(h($read['order_history_id'])); ?>">
                <input type="hidden" value= "<?php print $token; ?>" name="token">
              </form>
            </td>
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