<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

// ログイン済みか確認し、trueならトップページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// post送信されたnameを変数に格納
$name = get_post('name');
// post送信されたpasswordを変数に格納
$password = get_post('password');

// DB接続
$db = get_db_connect();

// nameに一致するユーザー情報をひとつ取得する→ユーザーが存在しなければ、エラー表示してlogiｎページへリダイレクト
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

// sessionにメッセージを格納する。管理者なら管理ページへリダイレクト
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);