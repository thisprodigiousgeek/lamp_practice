<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>該当の注文の合計金額</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
          <tr>
            <td><?php print($order['order_id']); ?></td>
            <td><?php print($order['created']); ?></td>
            <td><?php print(get_order_price($db, $order['order_id'])); ?>円</td>
            <td>
            
            <form method="post" action="order_detail.php">
                <input type="hidden" name="token" value=<?php print $token ?>>
                <input type="submit" value="購入明細表示" class="btn btn-secondary delete">
                <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
            
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <p class="text-right">合計金額: <?php print number_format($total_price); ?>円</p>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>