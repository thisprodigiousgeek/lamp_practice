<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続,ログインしているユーザーの情報を$userに格納
$db = get_db_connect();
$user = get_login_user($db);
//ログインしているユーザーのカートの中にある全ての商品の情報を$cartsに格納
$carts = get_user_carts($db, $user['user_id']);
//商品購入処理。購入できないときはエラーを、購入できるときはデータベースから購入された分の在庫数を減らし、最後にカートを削除する。
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 
//カートの中の全ての商品の合計額を計算
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';