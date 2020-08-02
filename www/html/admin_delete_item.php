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

//ログイン中のユーザー情報を取得する関数を変数に代入
$user = get_login_user($db);

//取得したユーザー情報が管理者でなかった場合、ログイン画面へ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//送られてきたitem_idの値を取得する関数を変数に代入
$item_id = get_post('item_id');

if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}

//管理画面へ
redirect_to(ADMIN_URL);