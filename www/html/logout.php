<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

//セッションをスタートする
session_start();

//$_SESSIONに配列を代入
$_SESSION = array();

$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy();

// ビューの読み込み。
redirect_to(LOGIN_URL);

