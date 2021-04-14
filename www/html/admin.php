<?php
//管理ページ

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

// ログインしていない場合はログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインユーザIDを取得
$user = get_login_user($db);

//管理者でない場合、ログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}


$items = get_all_items($db);

//admin_viewファイルを読み込み
include_once VIEW_PATH . '/admin_view.php';
