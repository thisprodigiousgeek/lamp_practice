<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// POST値取得
$name = get_post('name');
$password = get_post('password');
// DB接続
$db = get_db_connect();

// ログイン処理
$user = login_as($db, $name, $password);
if( $user === false){
  // 異常メッセージ
  set_error('ログインに失敗しました。');
  // ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// 正常メッセージ
set_message('ログインしました。');
// TYPE: 1でadmin.phpにリダイレクト
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
// ホームページにリダイレクト
redirect_to(HOME_URL);