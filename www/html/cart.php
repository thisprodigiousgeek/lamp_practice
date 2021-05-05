<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
// ログインしていなかったらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDOを利用してDB接続
$user = get_login_user($db);//DBからログインユーザ情報を取得

$carts = get_user_carts($db, $user['user_id']);//DBからログインユーザのカート情報を取得

$total_price = sum_carts($carts);//カート内の商品の合計金額を取得

include_once VIEW_PATH . 'cart_view.php';//カートページの読み込み