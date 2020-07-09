<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();

//ログインユーザーの情報を取得する関数を変数に代入
$user = get_login_user($db);

//ログインユーザーのカート内商品情報を取得する関数を変数に代入
$carts = get_user_carts($db, $user['user_id']);

//falseが返ってきたら以下のエラーメッセージを表示、再度カートページへ。 
if(purchase_carts($db, $carts) === false){ 
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

//カート内商品の合計金額を取得する関数を変数に代入
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';