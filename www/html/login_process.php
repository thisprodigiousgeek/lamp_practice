<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//ログインされている状態ならばホーム画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//post送信されたものを取得
$name = get_post('name');
$password = get_post('password');

//DB接続
$db = get_db_connect();

//ログイン処理
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);