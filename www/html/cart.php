<?php
/*
* カート用ファイル
*/
require_once '../conf/const.php'; //定数関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php'; //共通関数ファイルの読み込み
require_once MODEL_PATH . 'user.php'; //ユーザーデータ用関数ファイルの読み込み
require_once MODEL_PATH . 'item.php'; //商品用関数ファイルの読みこみ
require_once MODEL_PATH . 'cart.php'; //カート用関数ファイルの読み込み

//セッション開始、再開
session_start();

//ログイン可否判断
if(is_logined() === false){
  //ログインしていなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//データベースへ接続（sql実行準備）
$db = get_db_connect();

//ログインユーザーの情報を取得して、変数へ代入
$user = get_login_user($db);
//ログインユーザーのカート情報を取得
$carts = get_user_carts($db, $user['user_id']);
//カート内の商品合計額を割り出して、変数へ代入
$total_price = sum_carts($carts);

//カートページへ遷移
include_once VIEW_PATH . 'cart_view.php';