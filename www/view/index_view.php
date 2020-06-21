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
    <h1>商品一覧</h1>
    <!--エラーがあれば表示-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
        <!--$itemsがfalseではなければ（if文を書く必要がある)-->
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <!--viewでエスケープ処理-->
              <?php print htmlspecialchars($item['name'] , ENT_QUOTES , 'UTF-8'); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="token" value ="<?php print $token; ?>" >
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
  <div class="container">
    <br>
    <h1>人気ランキング‼‼</h1>
    <table  class="table table-bordered text-center">
      <thead class="thead-light">
      <tr>
        <th>順位</th>
        <th>商品画像</th>
        <th>商品名</th>
      </thead>
      <tbody>
      </tr>
      <?php $rank_num = 1; ?>
      <?php for($i = 0 ; $i < count($rank) ; $i++){ ?>
      <tr >
        <td>
          <?php print $rank_num; ?>
        </td>
        <td>
          <img class="item_image" src="<?php print(IMAGE_PATH . $rank[$i]['image']); ?>" >
        </td>
        <td>
          <?php print htmlspecialchars($rank[$i]['name'] , ENT_QUOTES , 'UTF-8'); ?>
        </td>
      </tr>
      <?php $rank_num++; ?>
      <?php  } ?>
      </tbody>
    </table>
  </div>
</body>
</html>