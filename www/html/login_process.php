<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

//ログインしていなければログイン画面へ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//送られてきたnameの値を取得する関数を変数に代入
$name = get_post('name');
//送られてきたpasswordの値を取得する関数を変数に代入
$password = get_post('password');

//DBに接続する関数を変数に代入
$db = get_db_connect();

$user = login_as($db, $name, $password);
//ログインに失敗した時
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
//ログインユーザーがadminだった場合、管理画面へ
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);