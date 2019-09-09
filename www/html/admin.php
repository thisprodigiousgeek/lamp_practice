<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

// ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);

}
// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//admin用のログインチェック用関数を利用
if(is_admin($user) === false){

// ユーザー名が違った場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);

}

//$itemsに全レコード代入
$items = get_all_items($db);

// ビューの読み込み。
include_once '../view/admin_view.php';
