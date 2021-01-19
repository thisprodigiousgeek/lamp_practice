<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
$_SESSION = array();
//セッションクッキーのパラメーターを取得
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  //情報が保存されている場所のパス
  $params["path"],
  //クッキーのドメイン
  $params["domain"],
  //クッキーはセキュアな接続のみ送信
  $params["secure"],
  //クッキーはHTTPを通してのみアクセス可能
  $params["httponly"]
);
//セッションに登録されたデータを全て破棄する
session_destroy();

redirect_to(LOGIN_URL);

