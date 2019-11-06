<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//functions.php user_idセッションが空かのチェック空ならログインページ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
//$userにmyadminから取得したユーザー情報を格納
$user = get_login_user($db);
//functions.php 各変数にカートidと数量を格納
$cart_id = get_post('cart_id');
$amount = get_post('amount');
//model/cart.php amountを変更するsql文
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

redirect_to(CART_URL);