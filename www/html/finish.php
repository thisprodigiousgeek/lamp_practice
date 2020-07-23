<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ログインユーザ情報取得
$user = get_login_user($db);
// ユーザのカート情報取得
$carts = get_user_carts($db, $user['user_id']);

// 購入処理
if(purchase_carts($db, $carts) === false){
  // 異常メッセージ
  set_error('商品が購入できませんでした。');
  // 異常が出た場合カートページにリダイレクト
  redirect_to(CART_URL);
} 
// カート内の合計金額
$total_price = sum_carts($carts);
// HTMLエンティティ化
$user = entity_array($user);
$carts = entity_arrays($carts);
// 購入後画面のviewファイル出力
include_once '../view/finish_view.php';