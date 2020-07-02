<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// 送信されたuser_idに一致するカートの中身を取得（全て） cart.php
$carts = get_user_carts($db, $user['user_id']);

// カートの商品の合計金額を計算する
$total_price = sum_carts($carts);

include_once VIEW_PATH . 'cart_view.php';