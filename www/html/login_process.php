<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
//sessionがセットされてれば直接ホームページへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}
//$各関数にpostで取得した情報を格納
$name = get_post('name');
$password = get_post('password');

$db = get_db_connect();

//model/user.php $userがセットされなければエラー
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