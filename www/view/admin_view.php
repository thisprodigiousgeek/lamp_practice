<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>商品管理</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>商品管理</h1>

  <?php include VIEW_PATH . 'templates/messages.php'; ?>

  <form method="post" action="admin_insert_item.php" enctype="multipart/form-data">
    <div>
      <label for="name">名前: </label>
      <input type="text" name="name" id="name">
    </div>
    <div>
      <label for="price">価格: </label>
      <input type="number" name="price" id="price">
    </div>
    <div>
      <label for="stock">在庫数: </label>
      <input type="number" name="stock" id="stock">
    </div>
    <div>
      <label for="image">商品画像: </label>
      <input type="file" name="image" id="image">
    </div>
    <div>
      <label for="status">ステータス: </label>
      <select name="status" id="status">
        <option value="open">公開</option>
        <option value="close">非公開</option>
      </select>
    </div>
    
    <input type="submit" value="商品追加">
  </form>


  <?php if(count($items) > 0){ ?>
    <table>
      <thead>
        <tr>
          <th>商品画像</th>
          <th>商品名</th>
          <th>価格</th>
          <th>在庫数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item){ ?>
        <tr class="<?php print(is_open($item) ? '' : 'close'); ?>">
          <td><img src="<?php print(IMAGE_PATH . $item['image']);?>" class="item_image"></td>
          <td><?php print($item['name']); ?></td>
          <td><?php print($item['price']); ?></td>
          <td>
            <form method="post" action="admin_change_stock.php">
              <input type="number" name="stock" value="<?php print($item['stock']); ?>">
              個
              <input type="submit" value="変更">
              <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
            </form>
          </td>
          <td>

            <form method="post" action="admin_change_status.php">
              <?php if(is_open($item) === true){ ?>
                <input type="submit" value="公開 → 非公開">
                <input type="hidden" name="changes_to" value="close">
              <?php } else { ?>
                <input type="submit" value="非公開 → 公開">
                <input type="hidden" name="changes_to" value="open">
              <?php } ?>
              <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
            </form>

            <form method="post" action="admin_delete_item.php">
              <input type="submit" value="削除">
              <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
            </form>

          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p>商品はありません。</p>
  <?php } ?> 
  
</body>
</html>