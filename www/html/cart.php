<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

// セッションを開始
session_start();

// ログインされていなければログインページに戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザ情報の取得
$user = get_login_user($db);

// ユーザーのカート内情報を取得
$carts = get_user_carts($db, $user['user_id']);

// 合計金額
$total_price = sum_carts($carts);

// VIEWを読み込む
include_once VIEW_PATH . 'cart_view.php';