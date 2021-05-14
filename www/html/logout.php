<?php
// conf...confingの略　confingは設定という意味
// constとは　定義という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunction.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// セッション開始する
// $_SESSION変数に配列を代入
//
session_start();
$_SESSION = array();
$params = session_get_cookie_params();
// Cookieを削除する
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
// セッションを削除する
// ログインページに飛ぶ
session_destroy();

redirect_to(LOGIN_URL);

