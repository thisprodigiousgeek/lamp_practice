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
//item_idデータを関数get_postを利用し取得
$item_id = get_post('item_id');

//stockデータを関数get_postを利用し取得
$stock = get_post('stock');

//update_item_stock関数を利用し在庫数を変更
if(update_item_stock($db, $item_id, $stock)){
  
//在庫数の変更に成功した場合下記のメッセージを表示
  set_message('在庫数を変更しました。');
} else {
//在庫数の変更に失敗した場合下記のメッセージを表示
  set_error('在庫数の変更に失敗しました。');
}
// ビューの読み込み。
redirect_to(ADMIN_URL);