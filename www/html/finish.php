<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'cart.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数の呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOの取得
$db = get_db_connect();
// ログインuser情報取得用の関数の呼び出し
$user = get_login_user($db);
// ログインuserのカート情報取得用の関数の呼び出し
$carts = get_user_carts($db, $user['user_id']);
// カート商品の購入処理用の関数の呼び出し
if(purchase_carts($db, $carts) === false){
  // sessionにメッセージを追加する関数の呼び出し
  set_error('商品が購入できませんでした。');
  // cart画面にリダイレクト
  redirect_to(CART_URL);
} 
// cart商品の合計金額を計算する関数の呼び出し
$total_price = sum_carts($carts);
// ビューファイルの読み込み
include_once '../view/finish_view.php';