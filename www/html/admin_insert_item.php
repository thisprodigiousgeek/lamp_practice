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

//送られてきた各種の値を取得する関数を変数に代入
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
$image = get_file('image');
$token = get_post('token'); 

//トークンがあれば処理を行う(CSRF対策)
if(is_valid_csrf_token($token)) {
  if(regist_item($db, $name, $price, $stock, $status, $image)){
    set_message('商品を登録しました。');
  }else {
    set_error('商品の登録に失敗しました。');
  }
} else {
  set_error('不正な処理です');
}

//管理画面へ
redirect_to(ADMIN_URL);