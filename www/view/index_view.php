<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
  <style>
    .ranking_img{
      width:400px;
      height:250px;
    }
    .sort{
      text-align:center;
    }
  </style>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!-- <form action="index.php">
      <select class="form-select" aria-label="Default select example" name="sort">
        <option value="新着順" selected>新着順</option>
        <option value="安い順">価格の安い順</option>
        <option value="高い順">価格の高い順</option>
      </select>
      <input type="submit" value="並び替え">
    </form> -->

    <h1 class="sort"><?php print($key);?></h1>

    <p><?php print($total_items);?> 件中 <?php print(($page*4) - 3);?> ~ 
      <?php if($total_items >= ($page*4)){
              print($page*4);
            }else{
              print($total_items);
            }?>
            件目の商品
    </p>
        <!-- ページネーション -->
    <nav aria-label="Page navigation">
      <ul class="pagination">
      <?php for($i=1;$i <= $total_page;$i++){?>
        <!-- <li class="page-item"><a class="page-link" href="#">前へ</a></li> -->
        <li class="page-item"><a class="page-link" href="index.php?page=<?php print($i);?>"
        <?php if($sort === !false){?>
                style="background-color:none;"
        <?php }elseif($page === $i){?>
                style="background-color:#afb2b4;"
          <?php }?>><?php print($i);?></a></li>
        <!-- <li class="page-item"><a class="page-link" href="#">次へ</a></li> -->
      <?php }?>
      </ul>
    </nav>


    <?php if($sort === false){?>
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
                  <?php print(number_format($item['price'])); ?>円
                  <?php if($item['stock'] > 0){ ?>
                    <form action="index_add_cart.php" method="post">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                      <input type="hidden" name="token" value="<?php print($token);?>">
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
    <?php }else{?>
      <div class="card-deck">
        <div class="row">
        <?php foreach($sort as $key){ ?>
          <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
                <?php print(h($key['name'])); ?>
              </div>
              <figure class="card-body">
                <img class="card-img" src="<?php print(IMAGE_PATH . $key['image']); ?>">
                <figcaption>
                  <?php print(number_format($key['price'])); ?>円
                  <?php if($key['stock'] > 0){ ?>
                    <form action="index_add_cart.php" method="post">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print($key['item_id']); ?>">
                      <input type="hidden" name="token" value="<?php print($token);?>">
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
    <?php }?>

    <h1>人気ランキング</h1>
    <table class="table table-bordered">
      <thead class="thead-light">
      <?php foreach($ranking as $key=>$value){?>
        <tr>
          <td><?php print($key+1)?>位</td>
          <td><?php print($value['name']);?></td>
          <td><img class="ranking_img" src="<?php print(IMAGE_PATH . $value['image']); ?>"></td>
        </tr>
      <?php }?>
      </thead>
    </table>
  </div>
</body>
</html>