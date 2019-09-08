<?php
//定数設定ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザー関係関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
//商品関係関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

session_start();
//ログイン済みでない場合、
if(is_logined() === false){
  //ログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

//データベースに接続
$db = get_db_connect();

//ユーザー情報を連想配列として取得
$user = get_login_user($db);

//管理者じゃない場合、
if(is_admin($user) === false){
  //ログインページへリダイレクト
  redirect_to(LOGIN_URL);
}

//商品情報を連想配列として取得
$items = get_all_items($db);

//商品管理ページ読み込み
include_once '../view/admin_view.php';
