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

//トークン生成
$token = get_csrf_token();

//データベース接続
$db = get_db_connect();

//ログインユーザーのデータを取得
$user = get_login_user($db);

//$userのtypeがUSER_TYPE_ADMINだった場合
if ($user['type'] === USER_TYPE_ADMIN){
  //管理者ページ
  $orders = get_all_order($db);
} else {
  //購入履歴表示
  $orders = get_user_orders($db, $user['user_id']);
}

//ビューの読み込み
include_once '../view/order_view.php';