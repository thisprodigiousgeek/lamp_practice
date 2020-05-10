<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($items) > 0){ ?>
    <p class="lead">
      <span class="pr-3">注文番号：<?php print($order_id) ?></span>
      <span class="pr-3">購入日時：<?php print($items[0]['order_date'])?></span>
      <span>合計金額：<?php print ($sum_purchase) ?>円</span>
    </p>
    <table class="table table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入金額</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item){ ?>
            <tr>
                <td><?php print($item['name']) ?></td>
                <td><?php print($item['purchase_price']) ?>円</td>
                <td><?php print($item['item_amount']) ?></td>
                <td><?php print($item['purchase_price'] * $item['item_amount']) ?>円</td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <?php } ?>
  </div>
</body>
</html>