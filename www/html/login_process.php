<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイル読み込み
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//ログインされなかったらホームページへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//名前とパスワードのポスト取得
$name = get_post('name');
$password = get_post('password');

//DB接続
$db = get_db_connect();

//名前とパスワード接続
$user = login_as($db, $name, $password);
//ユーザーではなかったらエラー
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

//ユーザーが管理者であれば管理者ページへ
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
//ホームページへ
redirect_to(HOME_URL);