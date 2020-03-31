<?php
// クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>

  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print htmlspecialchars(STYLESHEET_PATH . 'index.css'); ?>">
</head>

<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>


  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <form method="get" name="sort_form">
      <select name="sort">
        <option name="option" value="new" <?php if ($sort === 'new' || $sort === '') { ?>selected<?php } ?>>新しい順</option>
        <option name="option" value="low" <?php if ($sort === 'low') { ?>selected<?php } ?>>価格の安い順</option>
        <option name="option" value="high" <?php if ($sort === 'high') { ?>selected<?php } ?>>価格の高い順</option>
      </select>
      <!-- 選んだ方式で並べ替えを実施 -->
      <script>
        document.sort_form.addEventListener('change', function() {
          document.sort_form.submit();
        }, false);
      </script>
    </form>

    <div class="card-deck">
      <div class="row">
        <?php foreach ($items as $item) { ?>
          <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
                <?php print htmlspecialchars($item['name']); ?>
              </div>
              <figure class="card-body">
                <img class="card-img" src="<?php print htmlspecialchars(IMAGE_PATH . $item['image']); ?>">
                <figcaption>
                  <?php print htmlspecialchars(number_format($item['price'])); ?>円
                  <?php if ($item['stock'] > 0) { ?>
                    <form action="index_add_cart.php" method="post">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print htmlspecialchars($item['item_id']); ?>">
                      <input type="hidden" name="token" value="<?php print htmlspecialchars($token) ?>">
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