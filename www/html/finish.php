<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// 送信されたuser_idに一致するカートの中身を取得 cart.php
$carts = get_user_carts($db, $user['user_id']);

// カートの中身をチェック（validate_cart_purchase）して問題なければ、stockの在庫数を更新しカートから削除
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

// カートの商品の合計金額を計算する
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';