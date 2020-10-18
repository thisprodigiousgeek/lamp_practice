<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

//トークンの照合
if(is_valid_csrf_token($_POST['token'])) {
  //トークンの削除
  $token = '';
} else {
  redirect_to(LOGOUT_URL);
}

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

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);