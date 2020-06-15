<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>ご購入ありがとうございました！</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>ご購入ありがとうございました！</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($carts) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($carts as $cart){ ?>
          <tr>
            <td><img src="<?php print(IMAGE_PATH . $cart['image']);?>" class="item_image"></td>
            <td><?php print htmlspecialchars($cart['name'] , ENT_QUOTES , 'UTF-8'); ?></td>
            <td><?php print(number_format($cart['price'])); ?>円</td>
            <td>
                <?php print($cart['amount']); ?>個
            </td>
            <td><?php print(number_format($cart['price'] * $cart['amount'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <p class="text-right">合計金額: <?php print number_format($total_price); ?>円</p>
    <?php } else { ?>
      <p>カートに商品はありません。</p>
    <?php } ?> 
  </div>
  <table class="table table-bordered">
     <thead class="thead-light">
    <tr>
      <th>注文番号</th>
      <th>合計金額</th>
      <th>注文日時</th>
      <th>注文詳細</th>
    </tr>
    <?php 
    for($i = 0 ; $i < count($test) ; $i++){ ?>
    <tr>
      <td>
        <?php print $test[$i]['order_id']; ?>
      </td>
      <td>
        <?php print $test[$i]['total_price']; ?>
      </td>
      <td>
        <?php print $test[$i]['date']; ?>
      </td>
      <td>
        <form method="post" action="details_cont.php">
          <input type="submit" value="詳細" name="details">
          <input type="hidden" value="<?php print $test[$i]['order_id']; ?>" name="order_id">
        </form>
      </td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>