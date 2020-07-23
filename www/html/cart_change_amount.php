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
// ログインしていない場合はログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ログインユーザ情報取得
$user = get_login_user($db);
// POST値取得
$cart_id = get_post('cart_id');
$amount = get_post('amount');
// 購入数変更
if(update_cart_amount($db, $cart_id, $amount)){
  // 正常メッセージ
  set_message('購入数を更新しました。');
} else {
  // 異常メッセージ
  set_error('購入数の更新に失敗しました。');
}
// cart.phpにリダイレクト
redirect_to(CART_URL);