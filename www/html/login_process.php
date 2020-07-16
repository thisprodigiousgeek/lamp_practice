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
$token = get_post('token');

$db = get_db_connect();

if (is_token($token) === true){
  $user = login_as($db, $name, $password);
  if($user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }
} else {
  set_error('不正なリクエストです。');
  redirect_to(LOGIN_URL);
}



if ($user['type'] === USER_TYPE_ADMIN){
  set_message('ログインしました。');
  redirect_to(ADMIN_URL);
} elseif ($user_['type'] !== USER_TYPE_ADMIN) {
  set_message('ログインしました。');
  redirect_to(HOME_URL);
} else {
  set_error('不正なリクエストです。');
  redirect_to(LOGIN_URL);
}
