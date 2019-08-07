<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>商品管理</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>商品管理</h1>

  <?php include VIEW_PATH . 'templates/messages.php'; ?>

  <?php if(count($carts) > 0){ ?>
    <table>
      <thead>
        <tr>
          <th>商品画像</th>
          <th>商品名</th>
          <th>価格</th>
          <th>購入数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($carts as $cart){ ?>
        <tr>
          <td><img src="<?php print(IMAGE_PATH . $cart['image']);?>" class="item_image"></td>
          <td><?php print($cart['name']); ?></td>
          <td><?php print($cart['price']); ?>円</td>
          <td>
            <form method="post" action="cart_change_amount.php">
              <input type="number" name="amount" value="<?php print($cart['amount']); ?>">
              個
              <input type="submit" value="変更">
              <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
            </form>
          </td>
          <td>

            <form method="post" action="cart_delete_cart.php">
              <input type="submit" value="削除">
              <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
            </form>

          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <p>合計金額: <?php print number_format($total_price); ?>円</p>
    <form method="post" action="finish.php">
      <input type="submit" value="購入する">
    </form>
  <?php } else { ?>
    <p>カートに商品はありません。</p>
  <?php } ?> 
  
</body>
</html>