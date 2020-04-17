<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$token = get_post('token');
$cart_id = get_post('cart_id');
$amount = get_post('amount');

if(is_valid_csrf_token($token)) {
  if(update_cart_amount($db, $cart_id, $amount)){
    set_message('購入数を更新しました。');
  } else {
    set_error('購入数の更新に失敗しました。');
  }
}else {
  set_error('不正な操作です。');
}

redirect_to(CART_URL);