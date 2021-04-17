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
// ログインuser情報取得用の関数を呼び出し
$user = get_login_user($db);
// cartidの取得、postデータ取得用の関数の呼び出し
$cart_id = get_post('cart_id');
// 個数の取得、postデータ取得用の関数の呼び出し
$amount = get_post('amount');
// カート商品の在庫数を更新する関数を呼び出し
if(update_cart_amount($db, $cart_id, $amount)){
  // sessionにメッセージを追加する関数の呼び出し
  set_message('購入数を更新しました。');
} else {
  // sessionにメッセージを追加する関数の呼び出し
  set_error('購入数の更新に失敗しました。');
}
// cart画面にリダイレクト
redirect_to(CART_URL);