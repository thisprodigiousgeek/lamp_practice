<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>

  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>ご購入履歴</h1>   

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日</th>
            <th>合計金額</th>
            <th>購入明細</th>
          </tr> 
        </thead>
        <tbody>
    
          <?php foreach($buy as $buys){ ?>
          <tr>
           
            <td><?php h($buys['buy_id']); ?></td>
            <td><?php h($buys['date']); ?></td>
            <td>
                <?php h($buys['total']); ?>円
            </td>
            <td>
            <form method='POST' action='buy_details.php'>
                <input type='submit' value='詳細を見る'>
                <input type='hidden' name='buy_id' value='<?php h(print $buys['buy_id']) ?>'>
          </form>
            </td>
        
          </tr>
          <?php } ?>
        </tbody>
      </table>
     
  </div>
</body>
</html>