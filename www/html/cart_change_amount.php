<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が格納されていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベスに接続し、ログインしているユーザーの情報を $usetに格納
$db = get_db_connect();
$user = get_login_user($db);
//フォームから送られてきた情報を格納
$cart_id = get_post('cart_id');
$amount = get_post('amount');
//カート内の商品の数を変更
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}
//リダイレクト
redirect_to(CART_URL);