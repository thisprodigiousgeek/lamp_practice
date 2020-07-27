<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'detail.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$order_datetime = date( 'Y-m-d H:i:s' );

$db = get_db_connect();
$user = get_login_user($db);

$carts = get_user_carts($db, $user['user_id']);

if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

print(insert_order($db, $user['user_id'], $order_datetime));

$order_id = $db->lastInsertId();

foreach($carts as $values){
  print(insert_detail($db, $values["item_id"], $order_id, $values["amount"], $values["price"]));
}

$total_price = sum_carts($carts);

include_once '../view/finish_view.php';