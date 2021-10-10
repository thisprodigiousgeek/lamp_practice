<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッション開始
session_start();

//$_SESSION配列定義
$_SESSION = array();

//session_get_cookie_params()関数が見当たらない
$params = session_get_cookie_params();

//クッキー削除
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);

//セッションファイル削除
session_destroy();

//トークン生成
$token = get_csrf_token();

//ログインページへリダイレクト
redirect_to(LOGIN_URL);

