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
//model/cart.php$cartにユーザーのカート情報を格納する
$carts = get_user_carts($db, $user['user_id']);
//model/cart.php $total_priceに合計金額を格納
$total_price = sum_carts($carts);

include_once '../view/cart_view.php';