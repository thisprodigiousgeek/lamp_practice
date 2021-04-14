<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);

//カート情報を取得
$carts = get_user_carts($db, $user['user_id']);

//合計金額を取得
$total_price = sum_carts($carts);

include_once VIEW_PATH . 'cart_view.php';