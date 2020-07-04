<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
// セッション開始
session_start();
// セッション変数を空にする
$_SESSION = array();
// セッションクッキーのパラメータ取得
$params = session_get_cookie_params();
// クッキーの有効期限を過去にして無効化
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
// セッションに登録されたデータを全て破棄する
session_destroy();
// ログイン画面にリダイレクト
redirect_to(LOGIN_URL);

