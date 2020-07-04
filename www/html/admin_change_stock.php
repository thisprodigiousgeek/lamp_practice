<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();

//ログイン中のユーザーIDを取得する関数を変数に代入
$user = get_login_user($db);

//取得したユーザー情報が管理者でなかった場合、ログイン画面へ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//送られてきたitem_idの値を取得する関数を変数に代入
$item_id = get_post('item_id');
//送られてきたstockの値を取得する関数を変数に代入
$stock = get_post('stock');
//送られてきたtokenの値を取得する関数を変数に代入
$token = get_post('token');

//トークンがあれば処理を行う(CSRF対策)
if(is_valid_csrf_token($token)) {
  if(update_item_stock($db, $item_id, $stock)){
    set_message('在庫数を変更しました。');
  } else {
    set_error('在庫数の変更に失敗しました。');
  }
} else {
  set_error('不正な処理です');
}

//管理者画面へ
redirect_to(ADMIN_URL);