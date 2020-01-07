<?php
require_once '../conf/const.php';//定数ファイルの読み込み
require_once MODEL_PATH . 'functions.php'; //関数ファイルの読み込み
//セッション開始
session_start();
$_SESSION = array(); //セッション初期化
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy();
redirect_to(LOGIN_URL);
