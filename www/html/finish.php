<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//ログインしていなかったらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDO利用してDBに接続
$user = get_login_user($db);//DBからログインユーザ情報を取得

$carts = get_user_carts($db, $user['user_id']);//DBからログインユーザのカート情報を取得
//商品を購入する関数を利用、falseが帰ってきたらエラーメッセージ
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
//カートページへリダイレクト
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);//カート内の合計金額を取得
//購入完了ページを読み込み
include_once '../view/finish_view.php';