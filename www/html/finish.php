<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

//セッションスタート
session_start();

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//DB接続
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);

//カート情報を取得
$carts = get_user_carts($db, $user['user_id']);
//ログイン中のuser_idを取得
$user_id = get_session('user_id');

//カート情報に問題があればcart_urlにリダイレクト
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 
//ordersとorder_detailsテーブルに追加
order_transaction($db,$user_id,$carts);

//カートの合計金額を取得
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';