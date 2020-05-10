<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
if (isset($_POST["csrf_token"]) && $_POST["csrf_token"]===$_SESSION['csrf_token']){
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$data = get_user_carts($db, $user['user_id']);
// $data = item_id,name,price,stock,status,image,cart_id,user_id,amount
// エスケープ処理を追加

$carts = change_htmlsp_array($data);
//ここに購入履歴と明細の処理を追加

if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);

include_once '../view/finish_view.php';
} else {
  print '不正なアクセスです';
}