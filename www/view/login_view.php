<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ログイン</title>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <h1>ログイン</h1>

  <?php include VIEW_PATH . 'templates/messages.php'; ?>

  <form method="post" action="login_process.php">
    <div>
      <label for="name">名前: </label>
      <input type="text" name="name" id="name">
    </div>
    <div>
      <label for="password">パスワード: </label>
      <input type="password" name="password" id="password">
    </div>
    <input type="submit" value="ログイン">
  </form>
  
</body>
</html>