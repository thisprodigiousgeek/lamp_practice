<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//cartデータに関するファイル読み込み
require_once MODEL_PATH . 'cart.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログインユーザーのデータを取得
$user = get_login_user($db);

//postデータの取得
$order_id = get_post('order_id');
$token = get_post('token');

//トークンチェック
if(is_valid_csrf_token($token) === false){
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//購入履歴表示
$order = get_user_order($db, $order_id);

//$userのtypeがUSER_TYPE_ADMINじゃない場合かつ$order_id内のuser_idと一致しない
if ($user['type'] !== USER_TYPE_ADMIN && $order['user_id'] !== $user['user_id']){
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//購入明細表示
$details = get_user_order_detail($db, $order_id);

//ビューの読み込み
include_once '../view/order_detail_view.php';