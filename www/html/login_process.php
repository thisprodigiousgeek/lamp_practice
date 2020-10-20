<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');
$password = get_post('password');

$db = get_db_connect();


$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

//postで送られたtokenを取得
$token = get_post('token');
//生成したトークンが合っていなければログイン画面へリダイレクト
if(is_valid_csrf_token($token) === false){
  redirect_to(LOGIN_URL);
}
//トークンセッションの削除
unset($_SESSION["csrf_token"]);

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);