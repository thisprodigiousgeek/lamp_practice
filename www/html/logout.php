<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// ログアウトのために、セッションを開始
session_start();
// session変数を初期化
$_SESSION = array();
// セッションに関連する設定を取得
$params = session_get_cookie_params();
//sessionに利用しているクッキーの有効期限を過去に設定することで無効化
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
// セッションIDを無効化
session_destroy();
// ログイン画面にリダイレクト
redirect_to(LOGIN_URL);

