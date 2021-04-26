<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'cart.php';
// 注文データに関する関数ファイルの読み込み
require_once MODEL_PATH . 'order.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数の呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOの取得
$db = get_db_connect();
// ログインuser情報取得用の関数を呼び出し
$user = get_login_user($db);

// GETリクエストのデータを取得
$order_id = get_get('order_id');

// ユーザtypeがadminかどうか判定
if ($user['type'] === USER_TYPE_ADMIN){
  // adminの場合、注文履歴を全取得
  $order = get_order($db, $order_id);
} else {
  if(!$order = get_order($db, $order_id,$user['user_id'])){
    set_error('購入明細を開けませんでした');
    redirect_to(ORDER_URL);
  }
}

// 注文明細データの取得関数を呼び出し
$details = get_order_details($db, $order_id);


// ビューファイルの読み込み
include_once VIEW_PATH . 'details_view.php';