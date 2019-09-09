<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

 // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
//get_user_cartsにデータを表示
$carts = get_user_carts($db, $user['user_id']);
//合計金額を表示
$total_price = sum_carts($carts);

// ビューの読み込み。
include_once '../view/cart_view.php';