<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
//session情報を削除する
$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy();
//再びログインページへ
redirect_to(LOGIN_URL);

