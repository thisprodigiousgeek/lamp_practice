<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が格納されていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続
$db = get_db_connect();
//ログインしているユーザーの情報を$userに格納
$user_b = get_login_user($db);
//エスケープ
$user = h ($user_b);
//adminユーザーか確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//全ての商品情報を取ってくる
$items = get_all_items($db);

include_once '../view/admin_view.php';
