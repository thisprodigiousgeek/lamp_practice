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
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?> 
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php h($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php h(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php h($item['item_id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php $token; ?>">
                
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      <table class="table table-bordered">
      <h1 class='ranking'>売り上げ ランキングTOP3</h1>
        <thead class="thead-light">
        
          <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
           
          </tr> 
        </thead>
        <tbody>
 
        <?php foreach($ranking as $r) { ?>
          <tr>
            <td><img  src="<?php print(IMAGE_PATH . $r['image']); ?>" width='370px'></td>
            <td><?php h($r['name']); ?></td>
            <td><?php h($r['price']); ?>円</td>
            <td>
                <?php h($r['sum(buy_details.amount)']); ?>匹
            </td>
            
          </tr>
          <?php } ?>
          <style>
          .ranking{
            margin:0 auto;
    text-align:center;
    margin-top:100px;

}
.table{


  
}
          </style>
        </tbody>
      </table>

      </div>
    </div>
  </div>
  
</body>
</html>