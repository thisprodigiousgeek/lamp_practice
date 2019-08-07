<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>商品一覧</h1>

  <?php include VIEW_PATH . 'templates/messages.php'; ?>

  <div class="flex">
    <?php foreach($items as $item){ ?>
    <div class="item">
      <figure>
        <img class="item_image" src="<?php print(IMAGE_PATH . $item['image']); ?>">
        <figcaption>
          <?php print($item['name']); ?><br>
          <?php print($item['price']); ?>円
        </figcaption>
      </figure>
      <form action="index_add_cart.php" method="post">
        <input type="submit" value="カートに追加">
        <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
      </form>
    </div>
    <?php } ?>
    
  </div>
  
</body>
</html>