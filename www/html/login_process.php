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
$token_form = get_post('token');

$db = get_db_connect();

$session_token = is_valid_csrf_token($token);
if($session_token === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
} else if($token_form !== $session_token){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

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