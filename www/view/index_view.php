<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>

<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div id="sort">
    <p>商品の並べ替え</p>
      <form method="GET">
        <label>
        <select name="sort_products">
          <option value="1" <?php if($sort === '1') print 'selected'; ?>>新着順</option>
          <option value="2" <?php if($sort === '2') print 'selected'; ?>>価格の安い順</option>
          <option value="3" <?php if($sort === '3') print 'selected'; ?>>価格の高い順</option>
        </select>
        </label>
        <label><input type="submit" name="sort" value="並べ替える"></label>
      </form>
  </div>
  
  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print(h($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format(h($item['price']))); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print(h($item['item_id'])); ?>">
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
  
  <h1 id="ranking-h1">人気ランキング</h1>
  <div id="ranking">
    <div class="ranking-item">
      <?php print '１位：'.$rankings[0]['name']; ?>
      <p><img class="ranking-img" src="<?php print(IMAGE_PATH . $rankings[0]['image']); ?>"></p>
      <p><?php print '売上数：'.$rankings[0]['total'].'個'; ?></p>
    </div>
    <div class="ranking-item">
      <?php print '２位：'.$rankings[1]['name']; ?>
      <p><img class="ranking-img" src="<?php print(IMAGE_PATH . $rankings[1]['image']); ?>"></p>
      <p><?php print '売上数：'.$rankings[1]['total'].'個'; ?></p>
    </div>
    <div class="ranking-item">
      <?php print '３位：'.$rankings[2]['name']; ?>
      <p><img class="ranking-img" src="<?php print(IMAGE_PATH . $rankings[2]['image']); ?>"></p>
      <p><?php print '売上数：'.$rankings[2]['total'].'個'; ?></p>
    </div>
  </div>

</body>
</html>