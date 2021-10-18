<?php header("X-FRAME-OPTIONS: DENY");?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(h(STYLESHEET_PATH . 'purchase.css')); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
        
        <?php if(count($histories) > 0){?>
            <table border="4">
            <tr>
                <th>注文番号</th><th>注文日時</th><th>合計金額</th>
            </tr>
            <?php foreach($histories as $row){?>
                
                <tr>
                    <td><?php print h($row['order_id']);?></td>
                    <td><?php print h($row['datetime']);?></td>
                    <td><?php print h($row['total']);?></td>
                    <td>
                        <form method="post" action="purchase_detail.php">
                            <input type="hidden" name="order_id" value="<?php print h($row['order_id']);?>">
                            <input type="hidden" name="datetime" value="<?php print h($row['datetime']);?>">
                            <input type="hidden" name="total" value="<?php print h($row['total']);?>">
                            <input type="hidden" name="token" value="<?php print h($token);?>">
                            <input type="submit" value="購入明細">
                        </form>
                    </td>
                </tr>
            
            <?php }?>
            </table>
        <?php }else {?>
            <p>購入履歴はありません</p>
        <?php }?>
    </div>
</body>
</html>