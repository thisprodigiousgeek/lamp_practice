<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//ログインしていない場合ログインページにリダイレクトする処理
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
//ログインしたアカウントが管理者アカウントではなかったらログインページにリダイレクトする処理
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//全ての商品情報を取得し表示する処理
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
