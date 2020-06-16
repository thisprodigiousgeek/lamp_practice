
<!DOCTYPE html>
<html lang="ja">
    <head>
      <?php include VIEW_PATH . 'templates/head.php'; ?>
      <title>注文詳細</title>
      <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'details.css'); ?>">
    </head>
    <body>
      <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
      
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
              <th>商品名</th>
              <th>商品価格</th>
              <th>個数</th>
          </tr>
          <?php
          for($i=0; $i<count($details_price); $i++){ ?>
          <tr>
              <td>
                  <?php print $details_item_name[$i]['name']; ?>
              </td>
              <td>
                  <?php print $details_price[$i]['item_price']; ?>
              </td>
              <td>
                  <?php print $details_price[$i]['item_amount']; ?>
              </td>
          </tr>
          <?php } ?>
      </table>

    </body>
</html>
