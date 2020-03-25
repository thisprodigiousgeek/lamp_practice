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

$carts = get_user_carts($db, $user['user_id']);


if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
}

//購入履歴・購入明細テーブルの更新
add_history($db, $user['user_id']);

add_detail($db, $carts);

delete_user_carts($db, $carts[0]['user_id']);


$total_price = sum_carts($carts);

include_once '../view/finish_view.php';