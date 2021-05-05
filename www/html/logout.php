<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
$_SESSION = array(); //セッションを削除
$params = session_get_cookie_params();//セッションクッキーのパラメータ取得
//セッションの期限を過去にして無効化
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
//セッションidを無効化
session_destroy();
//ログインページへリダイレクト
redirect_to(LOGIN_URL);

