<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();
//ログイン中のユーザーIDを取得する関数を変数に代入
$user = get_login_user($db);

//送られてきたcart_idの値を取得する関数を変数に代入
$cart_id = get_post('cart_id');

//送られてきたtokenの値を取得する関数を変数に代入
$token = get_post('token');

//トークンがあれば以下の処理を行う(CSRF対策)
if(is_valid_csrf_token($token)) {
  if(delete_cart($db, $cart_id)){
    set_message('カートを削除しました。');
  } else {
    set_error('カートの削除に失敗しました。');
  }
} else {
  set_error('不正な処理です');
}

redirect_to(CART_URL);