<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品情報の取得
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//ログインされなかったらログインページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//ログインされたユーザー接続
$user = get_login_user($db);

//管理者でなかったらログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//商品情報の接続
$items = get_all_items($db);
//viewへ
include_once VIEW_PATH . '/admin_view.php';
