<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

  <div class="container">
  <form action="index.php" method="get" class="text-right">
    <select name="sort">
      <option value="new" selected>新着順</option>
      <option value="cheap">価格の安い順</option>
      <option value="expensive">価格の高い順</option>
    </select>
    <input type="submit" value="変更">
  </form> 

    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print h($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print h(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print h(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  
</body>
</html>