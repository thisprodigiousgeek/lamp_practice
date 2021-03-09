<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品情報ファイルの読み込み
require_once MODEL_PATH . 'item.php';
//カート情報の読み込み
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();
//ログインされなかったらログインページ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//DB接続へ
$db = get_db_connect();
//ログインされたユーザー情報取得
$user = get_login_user($db);
//ユーザーのカート情報の取得
$carts = get_user_carts($db, $user['user_id']);
//トータル価格の取得
$total_price = sum_carts($carts);

//cart.phpへ
include_once VIEW_PATH . 'cart_view.php';