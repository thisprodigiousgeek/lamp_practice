<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>

  <div class="container">
    <h1>購入明細</h1>
    <?php if(count($items) === 0){ ?>
      <p>該当する明細はありません</p>
    <?php } else { ?>

    <p>注文番号：<?php print $_POST['order_id'];?></p>
    <p>購入日時：<?php print $_POST['purchased'];?></p>
    <p>合計金額：<?php print $_POST['total_price'];?>円</p>
    <?php } ?>
    <table class="table table-bordered text-center">
    <thead class="thead-light">
        <tr>
        <th>商品名</th>
        <th>購入価格</th>
        <th>個数</th>
        <th>小計</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item){ ?>
        <tr>
        <td><?php print h($item['name']) ;?></td>
        <td><?php print h($item['price']) ;?>円</td>
        <td><?php print h($item['amount']) ;?>個</td>
        <td><?php print $item['small_price'] ;?>円</td>
        </tr>
        <?php } ?>
    </tbody>
    </table>
    <a href="histories.php">購入履歴へ戻る</a>
  </div>
</body>
</html>