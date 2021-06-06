<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//セッションにtokenを保存し、ランダムな文字列を$tokenに代入
$token = get_csrf_token();

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//ユーザー情報を取得
$user = get_login_user($db);

//adminユーザーでなければログイン画面にリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//全てのitemを取得
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
