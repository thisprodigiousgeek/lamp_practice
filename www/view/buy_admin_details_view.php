
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>

  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>ご購入明細</h1>   

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <table class="table table-bordered">
        <thead class="thead-light">
       
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            
          </tr> 
        </thead>
        <tbody>
       
          <?php foreach($bu as $buy){ ?>
      
            
          <tr>
           
            <td><?php h($buy['buy_id']); ?></td>
            <td><?php h($buy['date']); ?></td>
            <td><?php h($buy['total']); ?>円</td>
            
            
            </tr>
            <?php } ?>
          </tr>
          
          
          
            
        
          </tr>
           
        </tbody>
    
      </table>
    
      <table class="table table-bordered">
        <thead class="thead-light">
       
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr> 
        </thead>
        <tbody>
       
          <?php foreach($buys as $buy){ ?>
      
            
          <tr>
           
            <td><?php h($buy['name']); ?></td>
            <td><?php h($buy['price']); ?>円</td>
            <td><?php h($buy['amount']); ?>個</td>
            
            <td><?php h($buy['price']*$buy['amount']); ?>円</td>
            </td>
            
          </tr>
            <?php } ?>
          
          
            
        
          </tr>
           
        </tbody>
      </table>
     
  </div>
</body>
</html>