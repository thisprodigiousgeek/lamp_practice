<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<h1>購入履歴画面</h1>
<?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文の合計金額</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($admin_history as $history) { ?>
        <tr>
            <td><?php print h($history['order_id']); ?></td>
            <td><?php print h($history['purchase_date']); ?></td>
            <td>
                <?php
                  foreach($admin_history_sum as $sum) {
                    if($sum['order_id'] === $history['order_id']) {
                      print $sum['SUM(price * amount)'];
                    }
                  }
                ?>
            </td>
            <td>
                <form method="post" action="purchase_details.php">
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                    <input type="hidden" name="order_id" value="<?php print $history['order_id']; ?>">
                    <input class="btn btn-block btn-primary" type="submit" value="購入明細表示">
                </form>
            </td>
        </tr>
            <?php } ?>
        </tbody>
      </table> 
<?php } else { ?>
<p>購入履歴はありません。</p>
<?php } ?>
</body>
</html>