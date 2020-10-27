<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

//セッションのスタート
session_start();
//ログインされていなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//postで送られたtokenを取得
$token = get_post('token');
//生成したトークンが合っていなければログイン画面へリダイレクト
if(is_valid_csrf_token($token) === false){
  redirect_to(LOGIN_URL);
}
//トークンセッションの削除
unset($_SESSION["csrf_token"]);

$db = get_db_connect();
$user = get_login_user($db);

$carts = get_user_carts($db, $user['user_id']);

if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);

//購入履歴テーブルへの挿入
if(regist_order_transaction($db, $user['user_id'], $total_price, $carts) === false){
  set_error('テーブルへの書き込みに失敗しました。');
  redirect_to(CART_URL);
}
//foreach($carts as $read){
//  dd($read['stock']);
//}

include_once '../view/finish_view.php';