<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- Bootstrap4読み込み -->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>サインアップ</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'signup.css'); ?>">
</head>
<body>
  <!-- ヘッダービュー読み込み -->
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <div class="container">
    <h1>ユーザー登録</h1>
    <!-- ログイン結果やエラーメッセージなどのビュー読み込み -->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <!-- signup_process.phpを実行 -->
    <form method="post" action="signup_process.php" class="signup_form mx-auto">
      <div class="form-group">
        <label for="name">名前: </label>
        <!-- name="name" -->
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">パスワード: </label>
        <!-- name="password" -->
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <div class="form-group">
        <label for="password_confirmation">パスワード（確認用）: </label>
        <!-- name="password_confirmation" -->
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
      </div>
      <input type="submit" value="登録" class="btn btn-primary">
    </form>
  </div>
</body>
</html>