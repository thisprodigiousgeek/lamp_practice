<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細画面</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<table class="table table-bordered">
    <thead>
        <tr>
        <th>注文番号</th>
        <th>購入日時</th>
        <th>合計金額</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><?php print $order_id; ?></td>
        <td><?php print $purchase_date['purchase_date']; ?></td>
        <td><?php print $total_price; ?></td>
        </tr>
    </tbody>
</table>
<h1>購入明細画面</h1>
<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
        <th>商品名</th>
        <th>商品価格</th>
        <th>購入数</th>
        <th>小計</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($details as $detail) { ?>
    <tr>
        <td><?php print h($detail['name']); ?></td>
        <td><?php print h($detail['price']); ?></td>
        <td><?php print h($detail['amount']); ?></td>
        <td><?php print h($detail['price'] * $detail['amount']); ?></td>
    </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>