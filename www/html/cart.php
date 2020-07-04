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

//ユーザーごとのカート内商品情報を取得する関数を変数に代入
$carts = get_user_carts($db, $user['user_id']);

//送られてきたtokenの値を取得する関数を変数に代入
$token = get_csrf_token();
//カート内商品の合計金額を算出する関数を変数に代入
$total_price = sum_carts($carts);

include_once VIEW_PATH . 'cart_view.php';