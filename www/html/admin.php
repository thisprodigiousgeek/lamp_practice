<?php
//defineの参照
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//セッション上でログインしてなければlogin.phpに返す
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//db.phpはitem.php経由でつながっている
$db = get_db_connect();
//userのデータを取得する
$user = get_login_user($db);
//?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$data = get_all_items($db);
$items = change_htmlsp_array($data);

include_once VIEW_PATH . '/admin_view.php';
