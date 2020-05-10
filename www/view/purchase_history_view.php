<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <h1>購入履歴</h1>
    <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($items) > 0){ ?>
      <table class="table table-bordered text-center">
          <thead class="thead-light">
            <tr>
              <th>注文番号</th>
              <th>購入日時</th>
              <th>合計金額</th>
              <th>詳細</th>
            </tr>
          </thead>
          <tbody>
              <?php foreach($items as $item){ ?>
              <tr>
                  <td class="pt-3"><?php print($item['order_id']) ?></td>
                  <td class="pt-3"><?php print($item['order_date']) ?></td>
                  <td class="pt-3"><?php print($item['purchase_sum']) ?>円</td>
                  <td>
                    <form action="<?php print (PURCHASE_DETAILS_URL) ?>" method="post">
                      <input class="btn btn-secondary p-1" type="submit" value="詳細">
                      <input type="hidden" name='order_id' value="<?php print $item['order_id'] ?>">
                    </form>
                  </td>
              </tr>
              <?php }?>
          </tbody>
      </table>
    <?php } ?>

    </div>
</body>
</html>