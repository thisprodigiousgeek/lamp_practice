<?php
//商品在庫変更

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

session_start();

// ログインしていない場合はログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインユーザー情報取得
$user = get_login_user($db);

//管理者でない場合、ログインページへリダイレクトする
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$stock = get_post('stock');

//在庫変更されたとき
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

//adminページへ、リダイレクト
redirect_to(ADMIN_URL);