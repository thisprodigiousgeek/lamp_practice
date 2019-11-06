<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//session(user_idが空白ならログインページへ)
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
//$userがadminかのチェック?is_adminで管理者以外の場合はfalseを返してる
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$items = get_all_items($db);

include_once '../view/admin_view.php';
